<?php

namespace common\components;

use Yii;
use yii\helpers\Html;

use common\models\Site;

use yii\helpers\Arrayhelper;
use yii\filters\PageCache;

use Imagick;

class Helper
{

  private static $directoryCache = [];

  private static $siteData = [];

    /**
     * Извлекаем запись из таблички. С параметрами.
     * Если записи для данной модели не сущестует, создается объект с такими параметрами (не сохранияется!)
     */
    public static function getTableRecord($class, $params){

        $class = strpos($class, '\\') === false
            ? '\common\models\\' . $class
            : $class;

        if( !class_exists($class) ){
            throw new \Exception('Class "'.$class.'" is not exists');
        }

        $query = $class::find()->where($params);
        $object = $query->one();

        if( !$object ){
            $object = new $class;
            foreach ($params as $k=>$v){
                $object->$k=$v;
            }
        }

        return $object;
    }

    /**
     * Добавляем http к ссылке, если не указано
     */
    public static function safetyUrl($url){

        if( !$url ){
            return $url;
        }

        if( $url[0] == '/' ){
            return $url;
        }

        $result = ( substr($url, 0, 7) == 'http://' || substr($url, 0, 8) == 'https://' )
            ? $url
            : 'http://' . $url;
        
        return trim($result);
    }

    public static function findFreePath($query, $path) {

        $pathCopy = $path;
        $queryCopy = clone($query);
        $count = $queryCopy->andWhere(['path' => $pathCopy])->count();

        // Если сущностей с таким path нет - возвращаем указанный path
        if( $count == 0 ){
            return $path;
        }

        // Если path занят - перебираем значения
        // path_[1+]
        $index = 1;
        do{
            $queryCopy = clone($query);
            $pathCopy = $path . '_' . ($index++);
            $count = $queryCopy->andWhere(['path' => $pathCopy])->count();

        } while ( $count > 0 );

        return $pathCopy;
  }

  static function rus2translit($string) {
      $converter = array(
          'а' => 'a',   'б' => 'b',   'в' => 'v',
          'г' => 'g',   'д' => 'd',   'е' => 'e',
          'ё' => 'yo',   'ж' => 'zh',  'з' => 'z',
          'и' => 'i',   'й' => 'j',   'к' => 'k',
          'л' => 'l',   'м' => 'm',   'н' => 'n',
          'о' => 'o',   'п' => 'p',   'р' => 'r',
          'с' => 's',   'т' => 't',   'у' => 'u',
          'ф' => 'f',   'х' => 'h',   'ц' => 'c',
          'ч' => 'ch',  'ш' => 'sh',  'щ' => 'w',
          'ь' => '_',  'ы' => 'y',   'ъ' => '',
          'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

          'А' => 'A',   'Б' => 'B',   'В' => 'V',
          'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
          'Ё' => 'Yo',   'Ж' => 'Zh',  'З' => 'Z',
          'И' => 'I',   'Й' => 'J',   'К' => 'K',
          'Л' => 'L',   'М' => 'M',   'Н' => 'N',
          'О' => 'O',   'П' => 'P',   'Р' => 'R',
          'С' => 'S',   'Т' => 'T',   'У' => 'U',
          'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
          'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'W',
          'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
          'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
      );
      return str_replace(array_keys($converter), $converter, $string);
  }

  static function str2url($str) {
      // переводим в транслит
      $str = self::rus2translit($str);

      // в нижний регистр
      $str = strtolower($str);

      // заменям все ненужное нам на "-"
      $str = preg_replace('~[^-a-z0-9-_]+~u', '_', $str);
      // удаляем начальные и конечные '-'
      $str = trim($str, "_");

      return (string)substr($str,0,128);
  }

  /**
   * TODO: remove
   */
  static function loadSite($host = null){

        // Определяем домен сайта
        $domain = $host
            ? $host
            : \common\components\Factory::getCurrentDomain();

        if( !isset(self::$siteData[$domain]) ){

            // Извлекаем данные сайта по домену
            $Site = Yii::$app->factory->loadSite($domain);
            Yii::$app->factory->initSiteEnvironment($Site);

            self::$siteData[$domain] = $Site;
        }

        return self::$siteData[$domain];
  }


   static public function crop($src, $dst, $data, $fixSize = null) {

        if( empty($src) ){
            throw new \Exception('File of source image is empty');
        }

        if( empty($dst) ){
            throw new \Exception('File of destination image is empty');
        }

        if( !file_exists($src) ){
            throw new \Exception('Incorrect path of source image file "'.$src.'"');
        }

        if( !isset($data['width']) || !isset($data['height']) || !isset($data['x']) || !isset($data['y']) ){
            throw new Exception('One of the required params is not set.' . print_r($data, true));
        }

        $imagick = new Imagick(realpath($src));

        $imageWidth = $data['width'];
        $imageHeight = $data['height'];

        $cropX = $data['x'];
        $cropY = $data['y'];

        if( $data['rotate'] != 0 ){

            $origSize = $imagick->getImageGeometry();            

            $pixel = new \ImagickPixel('#000000');
            $imagick->rotateImage($pixel, $data['rotate']);

            // корректируем координаты точки обрезки изображения
            // поскольку при повороте размер холста изменяется
            $rotatedSize = $imagick->getImageGeometry();

            //print_r($origSize);
            //print_r($rotatedSize);

            $dx = $rotatedSize['width'] - $origSize['width'];
            $dy = $rotatedSize['height'] - $origSize['height'];

            //print_r([$cropX, $cropY]);

            $cropX = intval($cropX - $dx/2);
            $cropY = intval($cropY - $dy/2);

            //print_r([$cropX, $cropY]);
            //die;

        }

        $imagick->cropImage( $imageWidth, $imageHeight, $cropX, $cropY );

        if ($fixSize) {
            $imagick->resizeImage( $fixSize[0], $fixSize[1], Imagick::FILTER_LANCZOS, 1 );
        }

        $imagick->writeImage($dst);

        return $dst;
      }

    /**
     * Название месяца в именительном падеже
     * @param $date
     * @return mixed
     */
    public static function rusMonth($date) {
        $monthNames = self::rusMonths();
        return $monthNames[intval(date('m', strtotime($date)))];
    }

    /**
     * Список месяцев в именительном падеже
     * @return array
     */
    public static function rusMonths()
    {
        return [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];
    }

    public static function debug($string){

        try{
            if( Yii::$app->request->get('debug') ){
                echo "<pre>{$string}</pre>";
            }
        } catch( \Exception $e ){

        }
    }

    /**
     * Выводит превью изображения указанных размеров.
     * Превью создается обрезанием исходного изображения
     * @param string $image исходное изображение
     * @param int $width необходимая ширина
     * @param int $height необходимая высота
     * @return string|void
     */
    public static function thumbnailUrl($image, $width, $height, $props=[]){

        if(!$image){// || !is_int($width) || !is_int($height)) {
            return;
        }

        //echo $image;
        $parts = explode('?', $image);
        $image = $parts[0] ?? null;
        $postfix = $parts[1] ?? null;

        if(!$image){// || !is_int($width) || !is_int($height)) {
            return;
        }

        $extParts = explode('.', $image);
        $ext = strtolower(end($extParts));

        if( !in_array( $ext, ['gif', 'jpg', 'jpeg', 'png', 'bmp']) ){
            return;
        }

        $filename = basename($image);
        $path = substr($image, 0, strpos($image, $filename));
        $thumbPath = $path.'.thumbs/'.$width.'x'.$height;




        if( file_exists(Yii::getAlias('@webroot').$image) ) {

            // проверка существования файла для указанного размера
            if(!file_exists(Yii::getAlias('@webroot').$thumbPath.'/'.$filename)) {

                //echo Yii::getAlias('@webroot').$thumbPath.'/'.$filename;

                // создание директории
                @mkdir(Yii::getAlias('@webroot').$thumbPath, 0777, true);
                @chmod(Yii::getAlias('@webroot').$thumbPath, 0777);

                // создание картинки
                try {
                    $imagick = new Imagick(Yii::getAlias('@webroot') . $image);
                   // self::autoRotateImage($imagick);

                    if(isset($props['crop']) && $props['crop']){
                        $imagick->cropThumbnailImage($width, $height);
                    }else{
                        $imagick->resizeImage($width, $height,false,1);
                    }

                    $imagick->flattenImages();
                    $imagick->writeImage(Yii::getAlias('@webroot') . $thumbPath . '/' . $filename);

                }catch (\ImagickException $e) {

                    return $image;
                }
            }

            return $thumbPath.'/'.$filename . ( $postfix ? '?' . $postfix : '' );
        }

        return $image;
    }

    public static function thumbnail($image, $width, $height, $props=[]) {

        $url = self::thumbnailUrl($image, $width, $height, $props);

        $options = [
            'width' => $width > 0 ? $width : null,
            'height' => $height > 0 ? $height :  null,
        ];

        if($props){
            $options = array_merge ($options, $props);
        }

        return Html::img($url, $options);
    }

    // Поворот изображения
      public function autoRotateImage($image) {
            $orientation = $image->getImageOrientation();

            switch($orientation) {
                case 3:
                    $image->rotateimage("#000", 180); // rotate 180 degrees
                    break;

                case 6:
                    $image->rotateimage("#000", 90); // rotate 90 degrees CW
                    break;

                case 8:
                    $image->rotateimage("#000", -90); // rotate 90 degrees CCW
                    break;
            }

            // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
            $image->setImageOrientation(0);
        }

    public static function filesizeFormat($value, $round = 2, $kb_name = 'кб', $mb_name = 'мб'){

        $kb = $value / 1024;
        $mb = $value / 1024 / 1024;

        if( $mb >= 1 ){
            return round($mb, $round) . $mb_name;
        }

        return round($kb, $round) . $kb_name;
    }

    public static function date($format, $time = null){

        if( $time === null ){
            return '';
        }

        $result = date($format, intval($time));

        $list = [
            "Jan" => "января",
            "Feb" => "февраля",
            "Mar" => "марта",
            "Apr" => "апреля",
            "May" => "мая",
            "Jun" => "июня",
            "Jul" => "июля",
            "Aug" => "августа",
            "Sep" => "сентября",
            "Oct" => "октября",
            "Nov" => "ноября",
            "Dec" => "декабря"
        ];

        return str_replace( array_keys($list), $list, $result );
    }

    function youtubeId($link){

        $delimeter = '/';

        if( strpos($link, 'watch?v=') !== false ){
            $delimeter = 'watch?v=';
        }

        $parts = explode($delimeter, $link);
        $end = end($parts);

        // Убираем лишние параметры
        $parts2 = explode('&', $end);
        $result = $parts2[0];
        
        return trim($result);
    }

    /**
     * Пример вызова
     * Helper::pluralize(5, ['# элемент', '# элемента', '# элементов']);
     */
    static public function pluralize($count, array $forms){

        if( intval($count) != floatval($count) ){
            $plural = $forms[1];
        } else {
            $plural = $count%10==1 && $count%100 != 11
                ? $forms[0]
                : ( $count%10 >= 2 && $count%10 <= 4 && ( $count%100 < 10 || $count%100 >= 20 )
                    ? $forms[1]
                    : $forms[2]
                  );
        }

        return str_replace('#', $count, $plural);
    }

    static function isLink($string){
        return substr($string, 0, 4) == 'http' ? true : false;
    }

    /**
     * Извлекаем гибридные справочники
     * для табличек с полями
     * id, title, site_id, region_id
     */
    function getHybridList($modelName, $filter, $params)
    {
        $site_id = isset($filter['site_id']) ? $filter['site_id'] : 0;
        $region_id = isset($filter['region_id']) ? $filter['region_id'] : 0;

        $showComment = isset($params['comment']) && $params['comment'] ? true : false;

        $model = '\common\models\\' . $modelName;

        if( !isset( self::$directoryCache[$modelName][$site_id][$region_id] ) ){

            if( !class_exists($model) ){
                throw new \Exception('Class "'.$model.'" is not exists');
            }

            $Query = $model::find()->indexBy('id')->orderBy(['title' => 'asc']);

            // Условие извлечение общего справочника
            $conditionOR = [
                'OR',
                ['AND', ['is', 'site_id', null], ['is', 'region_id', null]],
            ];

            // Если указан регион - добавляем в список записи для региона
            if( $region_id > 0 ){
                $conditionOR[] = ['=', 'region_id', $region_id];
            }

            // Если указан сайт - добавляем в список данные для сайта
            if( $site_id > 0 ){
                $conditionOR[] = ['=', 'site_id', $site_id];
            }

            $_list = $Query->andWhere($conditionOR)->andWhere(['trash_flag' => 0])->all();
            $list = [];

            // добавляем comment в скобках, если задано в параметрах
            foreach($_list as $k=>$v){
                $comment = $v['comment'] ? ' (' . $v['comment'] . ')' : '';
                $list[$k] = $showComment ? $v['title'] . $comment : $v['title'];
            }

            //echo $Query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql;die;
            self::$directoryCache[$modelName][$site_id][$region_id] = $list;
        }

        return self::$directoryCache[$modelName][$site_id][$region_id];
    }

    /**
     * Извлекаем список сущностей
     */
    public static function loadList($modelName, array $params){

        $model = '\common\models\\' . $modelName;

        if( !class_exists($model) ){
            throw new \Exception('Class "'.$model.'" is not exists');
        }

        $where = ArrayHelper::getValue($params, 'where', []);
        $andWhere = ArrayHelper::getValue($params, 'andWhere', []);
        $with = ArrayHelper::getValue($params, 'with', []);

        $pageSize = ArrayHelper::getValue($params, 'limit', 10);
        $editMode = ArrayHelper::getValue($params, 'mode', false);
        $orderBy = ArrayHelper::getValue($params, 'order', 'id DESC');

        $query = $model::find()->where($where);

        // Проходим по массиву andWhere, чтобы добавить дополнительные условия выборки
        foreach($andWhere as $whereBlock){
            $query->andWhere($whereBlock);
        }

        // добавляем with
        foreach($with as $withItem){
            $query->with($withItem);
        }

        if( !$editMode ){

            $Model = new $model();

            if( array_key_exists('title', $Model->attributes) ){
                if( array_key_exists('short', $Model->attributes) ){
                    $query->andWhere(['or', ['is not', 'title', null], ['is not', 'short', null]]);
                } else {
                    $query->andWhere(['is not', 'title', null]);
                }
            }

            if( array_key_exists('publish_flag', $Model->attributes) ){
                $query->andWhere(['publish_flag' => 1]);
            }

            // Юзеры многи не добавляют body в статьи - такие статьи должны отображаться
            // поэтому данное условие не допустимо
            //$query->andWhere(['is not', 'body', null]);
        }

        $itemCount = $query->count();

        // Пагинация
        $pages = new \yii\data\Pagination([
            'totalCount' => $itemCount,
            'defaultPageSize'=>$pageSize,
            'pageSize'=>$pageSize,
        ]);

        // Список
        $query->offset($pages->offset)->limit($pages->limit);

        $query->orderBy($orderBy);

        $list = $query->all();

        return [
            'query' => $query,
            'list' => $list,
            'pages' => $pages,
            'count' => $itemCount,
        ];
    }

    public static function getHomeUrl(){

        $Site = Yii::$app->factory->getSite();

        if( $Site->getSetting('portal_startpage') ){
            //echo 1;die;
            return '/home';
        }

        return '/';
    }

    public static function shareImage($html){

        preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $html, $matches);

        if( isset($matches[1]) ){
            $domain = Yii::$app->factory->site->getAddress();
            return "{$domain}{$matches[1]}";
        }

        return null;
    }

    /**
     * Очищаем анонс новости / статьи при выводе
     * TODO: Удалить везде
     * Ранее была задача чистит везде анонс от пустых переводов строки
     * Затем поялась задача вернуть возиожность нескольких переносов
     * сейчас отключено, если долгое время не возникнет комментов - удаляем везде
     */
    public static function cleanHtml($string){
        //$string = str_replace(['<p></p>', '<p>&nbsp;</p>'], '', $string);
        return $string;
    }

    public static function beginCache($key){

        $Site = Yii::$app->factory->site;
        $View = Yii::$app->view;
        $cachekey = $key . '_' . $Site->id;
        $UserMode = Yii::$app->access->canEditPage();

        return $View->beginCache($cachekey, [
            'enabled' => !$UserMode && !Yii::getAlias('@dev'),
            'duration' => 3600,
        ]);
    }

    public static function endCache(){
        return Yii::$app->view->endCache();
    }

    /*
    public static function cache($key, $function){

        $cache = Yii::$app->cache;
        $Site = Yii::$app->factory->site;
        $auth = Yii::$app->access->canEditPage();
        $cache_key = $key . '_' . $Site->id;

        if( $auth ){
            $data = $function($Site);
        }

        else {

            $data = $cache->get($cache_key);

            if( $data === false ){
                $data = $function($Site);
                $cache->set( $cache_key, $data, 600 );
            }
        }

        return $data;
    }*/

    public static function longIp(){
        return sprintf('%u', ip2long(\Yii::$app->request->getUserIP()));
    }

    public static function currentUrl($params){

        $Request = Yii::$app->request;
        $path = $Request->pathinfo;
        $query = $params + $Request->queryParams;

        return '/' . $path . '?' . http_build_query($query);
    }

    /**
     * Убираем exif информацию об ориентации картинки
     */
    public static function fixOrient($path) {

        try{
            $image = new Imagick($path);
            $image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);
            $image->writeImage($path);
        } catch( \Exception $e ){
            echo 'Exception caught: ', $e->getMessage(), "\n";
        }
    }

    /*
    public static function removeExif($path)
    {
        $exif = @exif_read_data($path, "IFD0", true);

        if ($exif !== false ) {

            try {
                $img = new \Imagick($path);
                $profiles = $img->getImageProfiles("icc", true);

                $img->stripImage();

                if (!empty($profiles)){
                    $img->profileImage("icc", $profiles['icc']);
                }

                $img->writeImage($path);
                $img->clear();
                $img->destroy();

                //echo "Removed EXIF data from $image. \n";

            } catch (Exception $e) {
                echo 'Exception caught: ', $e->getMessage(), "\n";
            }
        }
    }
    */

    public static function BodyClasses(){

        $Site = Yii::$app->factory->site;
        $Access = Yii::$app->access;
        $User = Yii::$app->user->identity;
        $cookies = Yii::$app->request->cookies;

        $rolename = false;
        if( $Access->canEditPage() ){
            $rolename = $User->role == $User::ROLE_ADMIN ? 'admin' : 'moderator';
        }

        // Задаем для сайт специальные классы в body
        $BodyClasses = [
            $Site->region ? $Site->region->sysname : 'eduface',
            ($User && $User->id) ? 'authuser' : '',
            $rolename ? 'role-' . $rolename : '',
            $Access->canEditPage(true) ? 'eip-edit_mode' : 'eip-view_mode',

        ];

        // Классы для spv
        if( self::showSpecialVersion() ){
            $BodyClasses[] = 'spv';
            $BodyClasses[] = $_COOKIE['colorstyle'] ?? 'wsite';
            $BodyClasses[] = $_COOKIE['fontstyle'] ?? 'mfontsize';
            $BodyClasses[] = ($_COOKIE['imgstyle'] ?? 1) == 0 ? 'imagesHidden' : '';
            $BodyClasses[] = ($_COOKIE['brailestyle'] ?? 0) == 1 ? 'braileFont' : '';
        }

        return join(' ', $BodyClasses);
    }

    public static function showSpecialVersion(){
        return $_COOKIE['spv'] ?? 0;
    }

    /**
     * При замене файловых путей на персональных сайтах
     * пришлось ввести метод перевода старых путей до файлов на новые 
     * чтобы юзер видели в fm выбранные картинки
     */
    public static function personaPathCompat($path, $Section){

        if( strpos($path, '/persona/') !== false ){

            if( $Section ){
                $parts = explode( '/', $path );
                $parts[4] = 'section'; 
                $parts[5] = $Section->id;
                return join('/', $parts);
            }
        }

        return $path;
    }

    /**
     * Извлекаем из указанного текста первый парграф
     */
    public static function firstParagraph($text, $limit = 100){
        
        $text = str_replace('<p></p>', '', $text);
        $parts = explode('</p>', $text);

        $part = array_shift($parts);
        $part = strip_tags($part);
        $part = mb_substr($part, 0, $limit);

        $dotPos = mb_strrpos($part, '.');
        $part = $dotPos === false ? $part : mb_substr($part, 0, $dotPos+1);
           
        return $part;
    }

    /**
     * Определяем путь до favicon для региона
     */
    public static function regionFavicon($Site){

        if( $Site->getSetting('favicon') ){
            return $Site->getSetting('favicon');
        }

        $Region = $Site->region ?? null;
        $Sysname = $Region->sysname ?? null;

        $hasOwnIcon = ['edumsko', 'educrimea', 'educhel', 'edusev'];
        $folder = in_array($Sysname, $hasOwnIcon) ? $Sysname : 'eduface';

        return '/common/favicons/'.$folder.'/favicon.ico';
    }

}
