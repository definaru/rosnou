<?php

use frontend\rating\assets\SupportMainAsset;

require(__DIR__ . '/container.php');

$config = [
    'id' => 'rating-web',

    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'frontend\rating\controllers',
    'runtimePath' => __DIR__ . '/../../../../runtime/frontend/rating',

    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',

    'components' => [

        // скрипты подключаются в assets
        'assetManager' => [
            'bundles' => [

                // jQuery
                // добавляется в head
                'yii\web\JqueryAsset' => [
                    //'js'=>['jquery' => '/js/vendor/jquery-1.10.2.min.js'],
                    'jsOptions' => [
                        'position' => \yii\web\View::POS_HEAD,
                    ],
                ],

                //'yii\web\JqueryAsset' => false,
                //'yii\jui\JuiAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
                //'yii\bootstrap\BootstrapPluginAsset' => false,
            ],
            'linkAssets' => false,
            'forceCopy' => true,
        ],

        'mailer' => [
            'viewPath' => '@app/mail',
            'htmlLayout' => '@app/mail/layouts/html',
            'textLayout' => '@app/mail/layouts/text',
        ],

        // Сессия для фронтент юзеров
        'session' => [
            'class' => \yii\web\CacheSession::class,
            'name' => 'PHPSESSID',
            'cache' => 'cache',
        ],

        // Конфигурация юзера
        'user' => [
            'class' => \common\components\User::class,
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['users/access/login'],
        ],

        'access' => [
            'class' => 'common\components\UserAccess'
        ],

        // Страница ошибок на фронте
        'errorHandler' => [
            'errorAction' => 'errors/handle',
        ],

        // Настройки csrf/cookie валидации на фронте
        'request' => [
            'enableCsrfValidation' => false,
            'enableCookieValidation' => true,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'M6SscdsfDF34326i1xmh-JbCds3r6X',
        ],

        'view' => [
            'class' => \frontend\rating\components\View::class,
            'defaultAssetBundle' => SupportMainAsset::class,
        ],
    ],
];


$AllowedIps = ['109.194.67.22', '46.146.231.95', '86.110.172.138', '127.0.0.1', '95.31.249.66', '91.223.25.15'];

if( @$_GET['test'] ){
    print_r($_SERVER);die;
}

$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'] ?? null;
$HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;

if ( in_array($REMOTE_ADDR, $AllowedIps) || in_array($HTTP_X_FORWARDED_FOR, $AllowedIps)  ) {

    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';

    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
        //'panels' => [
        //    'queue' => \zhuravljov\yii\queue\debug\Panel::class,
        //],
    ];

    //$config['bootstrap'][] = 'gii';
    //$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
