<?php

namespace frontend\rating\components;

class View extends \yii\web\View
{
    /**
     * @var
     */
    public $defaultAssetBundle;

    /**
     * @var string
     */
    public $imgFolder = 'img';

    /**
     * @var
     */
    public $pageHeader;

    /**
     * @var array
     */
    public $breadcrumbs;

    /**
     * @param $src
     * @param null $bundle
     * @return string
     */
    public function img($src, $bundle = null)
    {
        $bundle = $bundle ? $this->assetBundles[$bundle] : $this->assetBundles[$this->defaultAssetBundle];

        return $bundle->baseUrl . '/' . $this->imgFolder . '/' . $src;
    }
}