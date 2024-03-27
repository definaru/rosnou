<?php

namespace frontend\rating\assets;

class MapAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@frontend/rating/assets/src';

    public $css = [
    	        ['css/jqvmap.css'],
    ];

    public $js = [
        	'js/jquery.vmap.js',
        	'js/jquery.vmap.russia.js',
   	        'js/vmap.js',
    ];

    public $depends = [];
}