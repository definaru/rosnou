<?php
/**
 * Date: 23.01.14
 * Time: 22:47
 */

namespace common\widgets\elfinder;

use Yii;

class AdminLocalPath extends BasePath{

    public $path;
    public $baseUrl = '@web';
    public $basePath = '@webroot';

   function __construct() {
        $this->baseUrl = str_replace('/admin','',Yii::getAlias('@web'));
        $this->basePath = str_replace('/admin','',Yii::getAlias('@webroot'));
    }
    public function getUrl(){

        return Yii::getAlias($this->baseUrl.'/'.trim($this->path,'/'));
    }

    public function getRealPath(){

        $path = Yii::getAlias($this->basePath.'/'.trim($this->path,'/'));

        if(!is_dir($path))
            mkdir($path, 0777, true);
            @chmod($path, 0777);

        return $path;
    }

    public function getRoot(){

        $options = parent::getRoot();

        $realpath = $this->getRealPath();

        $options['path'] = $realpath;
        $options['alias'] = $this->getRootName($this->path);
        $options['URL'] = $this->getUrl();

        return $options;
    }

    /**
     * Извлекаем кастомное название директории
     */
    private function getRootName($realpath){

        // Alias по фргменту в пути
        /*

        $parstRename = [
            'persona/about' => 'О нас',
            'persona/news' => 'Новости',
            'persona/portfolio' => 'Портфолио',
            'persona/articles' => 'Публикации',
            'persona/folders' => 'Материалы',
            'persona/media' => 'Медиа галерея',
            'persona/delivery' => 'Рассылка',
            'head_reference/photo_image' => 'Главная',
            'generalimage' => 'Оформление',
        ];

        // переименовываем название корневого раздела по совпадению фрагмента в url
        foreach( $parstRename as $partName => $partTitle ){
            if( strpos( $realpath, $partName ) !== false ){
                return $partTitle;
            }
        }
        */

        // Alias по подразделу
        $parts = explode('/', trim($realpath, '/'));
        $name = 'Файлы подраздела';

        /*
        //$Site = Yii::$app->factory->getSite();

        if( isset($parts[3]) ){

            if( $parts[3] == 'section' ){

                $name = 'section';

                if( isset($parts[4]) ){

                      $model = $Site->template_index == 1
                          ? '\common\models\section'
                          : '\common\models\PersonaSection';

                      $section = $model::findOne($parts[4]);

                      if( !$section->title && $Site->template_index != 1 ){
                          $section = $Site->getSitemap()->getSection($section->route);
                      }

                      if( is_object($section) ){
                          $name = wordwrap($section->title, 100, "\n");
                      }
                }

            } else {
                $name = $parts[3];
            }
        }*/

        return $name;
    }
}
