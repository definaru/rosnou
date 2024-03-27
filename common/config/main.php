<?php

$params = require(__DIR__ . '/../../common/config/params.php');

require(__DIR__ . '/container.php');

$config = [
    'vendorPath' => __DIR__ . '/../../../composer/vendor',

    'language' => 'ru-RU',
    'sourceLanguage' => 'en-EN',
    'timeZone' => $params['timeZone'] ?? 'UTC',


    'bootstrap' => [
        'log'
    ],

    'params' => \yii\helpers\ArrayHelper::merge(
        $params,
        [
            'baseUrl' => 'https://localhost',
            'project.name' => 'Rating web',
            'adminEmail' => 'info@rating-web.ru	',
            'supportEmail' => 'info@rating-web.ru',
            'user.passwordResetTokenExpire' => 259200, // 3 суток
        ]
    ),

    'components' => [
        'commandBus' => [
            'class' => \common\components\CommandBus::class,
        ],

        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-EN'
                    //'fileMap' => [
                    //    'app' => 'app.php',
                    //    'app.auth' => 'auth.php'
                    //],
                ],
            ],
        ],

        'formatter' => [
            'sizeFormatBase' => 1000,
            'timeZone' => $params['timeZone'] ?? 'UTC',
            'dateFormat' => 'd MMMM Y',
            'datetimeFormat' => 'php:d.m.Y H:i',
            'timeFormat' => 'php:H:i',
        ],

        'cache' => [
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true,
            'servers' => [
                [
                    'host' => $params['memcached.host'] ?? 'localhost',
                    'port' => 11211,
                    'weight' => 100,
                ],
            ],
        ],

        'mailer' => [

            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath' => '@common/mail',
            //'htmlLayout' => '@common/mail/layouts/html',
            //'textLayout' => '@common/mail/layouts/text',

            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => isset($params['mail.useFileTransport']) ? $params['mail.useFileTransport'] : YII_ENV_TEST,
            'fileTransportCallback' => YII_ENV_TEST ? function($mailer, \yii\swiftmailer\Message $message) {
                return \yii\helpers\Inflector::transliterate($message->getSubject()) . '.test.email';
            } : null,

            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['ga@rosnou.ru' => 'Техническая поддержка rating-web.ru'],
            ],

            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'transport' => [
               'class' => 'Swift_SmtpTransport',
               'host' => $params['mail.host'] ?? 'localhost',
               'port' => '25',
               'username' => $params['mail.username'] ?? 'username',
               'password' => $params['mail.password'] ?? 'password',
            ],

        ],

        'db' => [

            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=' . $params['db.host'] . ';port=5432;dbname=' . $params['db.name'], // PostgreSQL

            'username' => $params['db.user'],
            'password' => $params['db.pass'],
            'charset' => 'utf8',
            'tablePrefix' => 'tbl_',

            'schemaMap' => [
                'pgsql' => [
                    'class' => 'yii\db\pgsql\Schema',
                ],
            ], // PostgreSQL
            'enableSchemaCache' => true,
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => true,
        ],

        'authManager' => [
            'class' => \common\components\rbac\PhpManager::class,
            'itemFile' => '@common/rbac/items.php',
            //'ruleFile' => '@common/rbac/rules.php',
            'defaultRoles' => ['guest'],
        ],
    ],

    'modules' => [
        'datecontrol' => [

            'class' => 'kartik\datecontrol\Module',

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                kartik\datecontrol\Module::FORMAT_DATE => 'php:Y-m-d',
                kartik\datecontrol\Module::FORMAT_TIME => 'php:H:i',
                //kartik\datecontrol\Module::FORMAT_TIME => 'HH:mm:ss',
                kartik\datecontrol\Module::FORMAT_DATETIME => 'php:Y-m-d H:i',
            ],

            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                kartik\datecontrol\Module::FORMAT_DATE => 'php:Y-m-d 00:00:00', // saves as unix timestamp
                kartik\datecontrol\Module::FORMAT_TIME => 'php:H:i:s',
                kartik\datecontrol\Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],

            // set your display timezone
            //'displayTimezone' => 'Europe/Moscow',

            // set your timezone for date saved to db
            //'saveTimezone' => 'Europe/Moscow',

            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,

            // use ajax conversion for processing dates from display format to save format.
            'ajaxConversion' => false,

            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                kartik\datecontrol\Module::FORMAT_DATE => [], // example
                kartik\datecontrol\Module::FORMAT_DATETIME => [], // setup if needed
                kartik\datecontrol\Module::FORMAT_TIME => [], // setup if needed
            ],
            // other settings
        ]

    ],
];

return $config;

