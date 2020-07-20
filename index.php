<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

$yii = YII_DEBUG ? '../YiiFramework/framework/yii.php' : '../YiiFramework/framework/yiilite.php';
$application = dirname(__FILE__).'/protected/components/MApp.php';
$config = dirname(__FILE__).'/protected/config/main.php';

require $yii;
require $application;

Yii::createApplication('MApp', $config)->run();
