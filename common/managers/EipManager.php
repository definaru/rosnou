<?php

namespace common\managers;

use Yii;

use common\components\Helper;
use common\components\Event;

class EipManager{

    private $Site = null;
    private $User = null;
    private $Request = null;
    private $Formatter = null;

    public function __construct($Site, $User, $Request, $Formatter){
        $this->Site = $Site;
        $this->User = $User;
        $this->Request = $Request;
        $this->Formatter = $Formatter;
    }

    /**
     * По названию модели определяем класс и создаем пустой объект
     */
    public function findClass($model){

        $model = str_replace('__', '\\', $model);
        $class = '\common\models\\' . $model;

        if( !class_exists($class) ){

            $class = '\frontend\edudep\models\\' . $model;

            if( !class_exists($class) ){
                throw new \Exception('Class "'.$class.'" not exists');
            }
        }

        return $class;
    }

    /**
     * Помещаем объект в корзину
     */
    public function removeObject($Object){

        if( method_exists($Object, 'trashRemove') ){
            $this->registerEvent(3, $Object, null);
            $Object->trashRemove();
        }
    }

    public function restoreObject($Object){

        if( method_exists($Object, 'trashRestore') ){
            $this->registerEvent(4, $Object, null);
            $Object->trashRestore();
        }
    }

    /**
     * Создаем объект
     */
    public function createObject($class, $params, $options = []){

        $params['site_id'] = $this->Site->id;

        $object = new $class;

        if( is_array($params) ){
            foreach($params as $k=>$v){
                if( ($k=='ajaxCreate' && $class=='\\common\\models\\EducationPeriod')
                    || array_key_exists($k, $object->attributes) ){
                    $object->$k = $v;
                } else {
                    throw new \Exception('Property "'.$k.'" on object "'.$class.'" not exists');
                }
            }
        }

        if( $object->save() ){

            // Делаем объект первым в списке
            if( array_key_exists('list_order', $object->attributes) ){

                $object->list_order = isset($options['last']) 
                    ? $object::find()->select('max(list_order)')->scalar() + 1
                    : $object::find()->select('min(list_order)')->scalar() - 1;

                $object->update();
            }
        }

        $errors = $object->getErrors();

        if( $errors ){
            $error = end($errors);
            throw new \yii\base\UserException($error[0]);
        }

        // регистрируем событие
        $this->registerEvent(1, $object, null);

        return $object;
    }

    /**
     * Загружаем модель с указанным классом и id
     */
    public function loadObject($class, $pk){

        $Model = $class::findOne($pk);

        if( !$Model ){
            throw new \yii\base\UserException('Model with class "'.$class.'" and id = '. $pk . ' not exists');
        }

        return $Model;
    }

    /**
     * Редактируем объект системы
     */
    public function editObject(&$model, $fields, $actions = []) {

        $fields = is_array($fields) ? $fields : [$fields];

        // Вычищаем поле от указанных системных слов
        // Поскольку многие пользователи копипастят их из других
        // полей
        // также вычищаем script и style теги
        $replaced = ['eip-view_block', 'eip-edit_block', '<script', '</script>', '<style', '</style>'];

        foreach($fields as $field => $value){
            if( $field ){

                if( is_string($value) ){
                    $value = trim($value, " \n");
                }
                
                $model->$field = str_ireplace($replaced, '', $value);
            }
        }

        // Выдаем ошибку при сохранении модели
        // Проверяем наличие ошибок только для того
        // поля которое сохраняем, чтобы не блокировать
        // возможность изменения значения текущего поля
        // из-за других полей
        $model->validate();
        $errors = $model->getErrors();

        if( isset($errors[$field]) ){
            throw new \yii\base\UserException($errors[$field][0]);
        }

        // Если ошибок не найдено
        // сохраняем только измененное поле
        // без валидации
        $model->save(false);

        // сохраняем типизированные поля
        $parts = explode('_', $field);
        $partsLast = end($parts);

        // получаем значение
        if( in_array( $partsLast, ['data', 'datetime'])){
            $result = $this->Formatter->asDate($model->$field);
        } else {
            $result = $model->$field;
        }

        $this->doActions($actions, $model, $field);

        // регистрируем событие
        $this->registerEvent(2, $model, $field);

        return $result;
    }

    /**
     * После обновления значения поя модели
     * применяем какие-либо действия
     */
    private function doActions($list, $model, $field){

        foreach($list as $action => $value){

            // Очищаем тумбы картинки
            if( $action  == 'clear_thumb'){
                $this->deleteThumbs( Yii::getAlias('@webroot') . $model->$field);
            }

            // обновляем превью документов
            if( $action  == 'preview_rebuild'){
                $model->preview_rebuild_flag = 1;
                $model->update(false);
            }
        }
    }

    /**
     * Регистрируем событие в системе
     */
    public function registerEvent($type, $Object, $field){

        $referrer = strtok($this->Request->referrer, '?');

        Event::register([
            'type' => $type,
            'url' => $referrer,
            'object' => $Object,
            'field' => $field
        ]);
    }

    /**
     * Сохраняем порядок сортировки для заданных элементов
     */
    public function sortObjects($class, $field, $positions, $first = 1){

        $positions = array_map( function($value){
            return intval($value);
        }, $positions );

        if(!empty($positions) && !empty($field)) {

            // получение списка
            $query = $class::find()
                ->andWhere(['trash_flag' => [0, 1]])
                ->andWhere(['id' => $positions])
                ->indexBy('id');

            $models = $query->all();

            foreach($positions as $position => $id) {

                if($id && array_key_exists($id, $models)) {

                    $models[$id]->$field = $position + $first;

                    if($models[$id]->isAttributeChanged($field)) {
                        if(!$models[$id]->update(false)) {
                            $errorList = $models[$id]->getErrors();
                            $error = end($errorList);
                            throw new \yii\base\UserException($error[0]);
                        }
                    }
                }
            }
        }


    }

    /**
     * Изменять данные моделей
     * по средствам js запросов можно только
     * админам и только конкретные поля конкретных моделей
     */
    public function checkModelAccess($model, $fields){

        $editableModels = [
            'PersonaContact' => ['map_zoom', 'marker_lat', 'marker_lng'],
            'OrgAddress' => ['zoom', 'marker_lat', 'marker_lng'],
        ];

        if( !isset($editableModels[$model]) ){
            throw new \yii\base\UserException('Модель "' . $model . '" не доступна для редактирования');
        }

        foreach($fields as $field => $value){
            if( !in_array($field, $editableModels[$model]) ){
                throw new \yii\base\UserException('Поле "'. $model . '->' . $field . '" не доступно для редактирования');
            }
        }

    }

    /*
     * Удаляем тумбы, созданные для картинки
     */
     public function deleteThumbs($path) {

        $path = strtok($path, '?');

        $dir = dirname($path);
        $name = basename($path);
        $files = glob( $dir . '/.thumbs/*/' . $name, GLOB_MARK );

        foreach($files as $file){
            if(is_file($file)) {
                unlink($file);
            }
        }
    }

}
