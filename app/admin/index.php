<?php
define('WEB_ROOT', realpath(dirname(__FILE__).'/../../'));
define('LIB_PATH', realpath('../../library'));
# 根据路径获取应用名称
$app_path = dirname(__FILE__);
$app_name = substr($app_path,strrpos($app_path,DIRECTORY_SEPARATOR)+1);
define('APP_NAME',$app_name);

require_once LIB_PATH.'/Bootstrap.php';

$app = new Library\Bootstrap();
$app->init();
