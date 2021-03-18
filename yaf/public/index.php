<?php

//ini_set("display_errors", "On");//打开错误提示
//ini_set("error_reporting",E_ALL);//显示所有错误

//设置时区
date_default_timezone_set('PRC');

/* 定义这个常量是为了在app.ini中引用*/
define('APP_PATH', dirname(__DIR__));

//$application = new Yaf_Application( APP_PATH . "/conf/app.ini");    //不使用命名空间
$application = new Yaf\Application( APP_PATH . "/conf/app.ini");    //使用命名空间

$application->bootstrap()->run();
