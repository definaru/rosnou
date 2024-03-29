<?php
/**
 * @var array $options
 */

require_once(__DIR__ . '/../php/elFinderConnector.class.php');
require_once(__DIR__ . '/../php/elFinder.class.php');
require_once(__DIR__ . '/../php/elFinderVolumeDriver.class.php');
require_once(__DIR__ . '/../php/elFinderVolumeLocalFileSystem.class.php');
require_once(__DIR__ . '/../php/elFinderVolumeYii.class.php');
require_once(__DIR__ . '/../../../../../app/common/components/Helper.php');

define('ELFINDER_IMG_PARENT_URL', Yii::$app->assetManager->getPublishedUrl(__DIR__."/../assets"));

// run elFinder
$connector = new elFinderConnector(new elFinder($options));
$connector->run();
