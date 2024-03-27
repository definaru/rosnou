<?php

namespace frontend\rating\assets;

class NewsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@frontend/rating/assets/src';

    public $css = [];

    public $js = [
        ['https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.4/goodshare.min.js'],
    ];

    public $depends = [];
}
