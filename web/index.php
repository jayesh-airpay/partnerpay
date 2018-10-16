<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_WARNING & ~E_PARSE);
// comment out the following two lines when deployed to production
define('YII_ENABLE_ERROR_HANDLER', true);
define('YII_ENABLE_EXCEPTION_HANDLER', true);
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/../config/web.php');

$obj = new yii\web\Application($config);
//var_dump($obj); exit;
$obj->run();
//(new yii\web\Application($config))->run();
