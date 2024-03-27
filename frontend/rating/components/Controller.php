<?php

namespace frontend\rating\components;

class Controller extends \yii\web\Controller
{
    /**
     * @var bool
     */
    public $defaultAsset = true;

    public function init()
    {
        if($this->defaultAsset) {
            $defaultAssetBundle = $this->view->defaultAssetBundle;

            $defaultAssetBundle::register($this->view);
        }
    }
}