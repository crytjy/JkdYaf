<?php
/**
 * JKDYAF 启动文件
 */

//设置时区
date_default_timezone_set('PRC');

define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__DIR__));
define('CONF_PATH', APP_PATH . DS . 'conf');

$config = parse_ini_file(CONF_PATH . "/app.ini", true);
$libPath = $config['common']['comLibsPath'] ?? '';
define('LIB_PATH', $libPath);
require $libPath . 'HttpServer.php';

$fun = $argv[1] ?? '';

$serverObj = HttpServer::getInstance($argv[2] ?? '');
$serverObj->$fun();