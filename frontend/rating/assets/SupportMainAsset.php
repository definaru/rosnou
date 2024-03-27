<?php

namespace frontend\rating\assets;

class SupportMainAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@frontend/rating/assets/src';

    public $css = [
        ['css/bootstrap.css'],
        ['css/font-awesome/font-awesome.min.css'],
        ['css/fontrosnou.css'],
        ['css/fonts.css'],
        ['css/animate.min.css'],
        ['css/jquery.formstyler.css'],
        ['css/flickity.css'],
        ['css/select2.css'],
        ['css/formValidation.min.css'],
        ['css/style.css'],
    ];

    public $js = [
        ['https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js', 'condition' => 'lt IE 9', 'position' => 1],
        ['https://oss.maxcdn.com/respond/1.4.2/respond.min.js', 'condition' => 'lt IE 9', 'position' => 1],

        'js/jquery-2.2.4.min.js',
        'js/js.cookie.js',
        'js/bootstrap.min.js',
        'js/jquery.easing.1.3.min.js',
        'js/imagesloaded.pkgd.min.js',
        'js/masonry.pkgd.min.js',
        'js/wow.min.js',
        'js/jquery.formstyler.min.js',
        'js/jquery.form.min.js',
        'js/flickity.pkgd.min.js',
        'js/jquery.tablesorter.min.js',
        'js/picnet.table.filter.min.js',
        'js/custom.js',
        'js/select2.min.js',
        'js/formvalidation/formValidation.min.js',
        'js/formvalidation/formValidation.examination.js',
        'js/formvalidation/bootstrap.min.js',
        'js/tmpl.min.js',
        'js/app.js',
        'js/jquery.tickets.js',
        'js/plugin-anti-spam.js',
        'js/plugins.js'
    ];

    public $depends = [
    ];
}
