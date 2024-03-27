<?php

require __DIR__ . '/container.php';

$config = [
    'id' => 'app-backend-rating',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\rating\controllers',
    'runtimePath' => __DIR__ . '/../../../../runtime/backend/rating',

    // Подключаем файловый менеджер
    'controllerMap' => [

        'elfinder' => [
            'class' => 'common\widgets\elfinder\AdminPathController',
            'access' => ['@'],
            'root' => [
                'path' => 'uploads',
                'driver' => 'Yii',
            ],
        ],

        'commonElfinder' => 'frontend\rating\controllers\ElfinderController',

    ],

    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => [
                '109.194.67.22',
                '78.25.121.73',
                '92.101.255.24',
                '90.188.14.67',
                '109.203.192.175',
                '127.0.0.1'
            ],  //allowing ip's
            'generators' => [
                'upmc-crud' => [
                    'class' => 'common\generators\crud\Generator',
                ],
                'upmc-model' => [
                    'class' => 'common\generators\model\Generator',
                ],
            ],
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // other module settings
        ],
    ],
    'components' => [
        'session' => [
            'name' => 'PHPSESSIDADM',
            'class' => 'yii\web\CacheSession',
            'cache' => 'cache',
        ],
        'user' => [
            'class' => \common\components\User::class,
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/access/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'user/access/error',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'q8553366Va1R7PfGwC_k9oUsJuU0GGsq',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'user/user/index',
                '/login' => 'user/access/login',
                'elfinder/xmanager' => 'commonElfinder/xmanager',
                'elfinder/<_a>' => 'elfinder/<_a>',
            ]
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['109.194.67.22','127.0.0.1', '95.31.249.66'], //['127.0.0.1', '::1', '50.62.10.149', '50.63.59.230']
    ];

    // $config['bootstrap'][] = 'gii';
    // $config['modules']['gii'] = [
    //     'class' => 'yii\gii\Module',
    //     'allowedIPs' => ['127.0.0.1'],
    // ];
}

return $config;
