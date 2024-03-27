<?php

// Bootstrap file
require(__DIR__ . '/../../../common/config/_header.php');
require(__DIR__ . '/../../../common/config/env.php');

require(__DIR__ . '/../../../../composer/vendor/autoload.php');
require(__DIR__ . '/../../../../composer/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../../common/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../../common/config/main.php'),
    require(__DIR__ . '/../config/main.php')
);

$application = new yii\web\Application($config);
$application->run();

// End file
require(__DIR__ . '/../../../common/config/_footer.php');
