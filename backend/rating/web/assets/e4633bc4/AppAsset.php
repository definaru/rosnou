<?php

namespace backend\rating\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';

    public $publishOptions = [
        'forceCopy' => true
    ];

    public $sourcePath = '@backend/rating/assets';

    public $css = [
        'app/css/app.css',
        'redactor/sup/sup.css',
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css'
    ];

    public $js = [
        'redactor/sup/sup.js',
        'app/js/sortable.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
