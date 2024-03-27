<?php
/**
 *
 */

namespace common\widgets\elfinder;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

/**
 * Class PathController
 *
 * @package common\widgets\elfinder
 */
class PathController extends BaseController{

    public $disabledCommands = ['netmount'];

    public $root = [
      'baseUrl' => '@web/files',
      'basePath' => '@webroot/files',
      'path' => ''
    ];

    public $watermark;
    private $_options;

    public function getOptions()
    {

        if($this->_options !== null){
            return $this->_options;
        }

        // Принимаем sizemode
        $this->_options['sizemode'] = Yii::$app->request->getQueryParam('sizemode', 1);

        // Даем пользователю право работать только со своей файловой папкой
        $root = Yii::$app->factory->site->getUploadUrl();
        $this->root['path'] = $root;

        $subPath = Yii::$app->request->getQueryParam('path', '');
        $subPath = str_replace($root, '', $subPath);

        // Init logger
        $Logger = new ElfinderLogger(Yii::$app->db);
        $this->_options['bind']['mkdir mkfile rename duplicate upload rm paste'] = array($Logger, 'log');

        $this->_options['roots'] = [];

        $root = $this->root;

        if(is_string($root)){
            $root = ['path' => $root];
        }

        if(!isset($root['class'])){
            $root['class'] = 'common\widgets\elfinder\LocalPath';
        }

        if(!empty($subPath)){
            if(preg_match("/\./i", $subPath)){
                $root['path'] = rtrim($root['path'], '/');
            }
            else{
                $root['path'] = rtrim($root['path'], '/');
                $root['path'] .= '/' . trim($subPath, '/');
            }
        }

        $root = Yii::createObject($root);

        /** @var \common\widgets\elfinder\LocalPath $root*/
        if($root->isAvailable()){
            $this->_options['roots'][] = $root->getRoot();
        }

        if(!empty($this->watermark)){

            $this->_options['bind']['upload.presave'] = 'Plugin.Watermark.onUpLoadPreSave';

            if(is_string($this->watermark)){
                $watermark = [
                  'source' => $this->watermark
                ];
            }else{
                $watermark = $this->watermark;
            }

            $this->_options['plugin']['Watermark'] = $watermark;
        }

        $this->_options = ArrayHelper::merge($this->_options, $this->connectOptions);

        return $this->_options;
    }

    public function getManagerOptions(){
        $options = parent::getManagerOptions();
        $options['url'] = Url::toRoute(['connect', 'path' => Yii::$app->request->getQueryParam('path', '')]);
        return $options;
    }
}
