<?php

namespace frontend\rating\assets;

class FormValidationAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@frontend/rating/assets/src';

    public $css = [];

    public $js = [
        'js/formvalidation/formValidation.registrate.js',
    ];

    public $depends = [
        SupportMainAsset::class,
    ];
}
