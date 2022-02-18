<?php
/**
 * JKDYAF 启动文件
 */
$fun = $argv[1] ?? '';
if (!$fun) {
    $color = '0;31';
    echo "\033[" . $color . "m missing argv 1. \033[0m" . PHP_EOL;
    exit();
}

define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__DIR__));
define('CONF_PATH', APP_PATH . DS . 'conf');

$config = parse_ini_file(CONF_PATH . '/app.ini', true);
$libPath = $config['common']['comLibsPath'] ?? '';
define('LIB_PATH', $libPath);
require $libPath . 'HttpServer.php';

$serverObj = HttpServer::getInstance($argv[2] ?? '');
$serverObj->$fun();
