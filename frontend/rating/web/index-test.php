<?php

if(!isset($_SERVER['HTTP_ENV']) OR $_SERVER['HTTP_ENV'] != 'TEST') {
    header('HTTP/1.0 404 not found');
    exit;
}

// Bootstrap file
require(__DIR__ . '/../../../common/config/_header.php');
require(__DIR__ . '/../../../tests/phpunit/config/env.php');

require(__DIR__ . '/../../../../composer/vendor/autoload.php');
require(__DIR__ . '/../../../../composer/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../../common/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../../common/config/main.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/routing.php'),
    require(__DIR__ . '/../../../tests/phpunit/config/main.php')
);

$application = new yii\web\Application($config);
$application->run();

// End file
require(__DIR__ . '/../../../common/config/_footer.php');
