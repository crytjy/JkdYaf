<?php

/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{

    /**
     * 加载文件
     */
    public function _initLoader()
    {
        Yaf\Loader::import(Lib_PATH . '/Common/Helper.php');

        //加载模型父类
        Yaf\Loader::import(Lib_PATH . '/JkdBaseModel.php');
    }

    public function _initConfig()
    {
        //把配置保存起来
        $arrConfig = Yaf\Application::app()->getConfig();
        Yaf\Registry::set('config', $arrConfig);

        //保存route
        $routeConfig = \JkdConf::get('route');
        Yaf\Registry::set('routeConf', $routeConfig);

        //保存redisKey
        $redisKeyConfig = \JkdConf::get('redisKey');
        Yaf\Registry::set('redisKeyConf', $redisKeyConfig);

        //保存redis配置
        $redisConfig = \JkdConf::get('redis');
        Yaf\Registry::set('redisConf', $redisConfig);
    }

    public function _initPlugin(Yaf\Dispatcher $dispatcher)
    {
        //注册一个插件
        $objSamplePlugin = new SamplePlugin();
        $dispatcher->registerPlugin($objSamplePlugin);
    }


    public function _initRoute(Yaf\Dispatcher $dispatcher)
    {
        //在这里注册自己的路由协议,默认使用简单路由
    }


    /**
     * 加载service类
     */
    public function _initServiceLoader()
    {
        $firstPath = APP_PATH . '/app/services/';
        $data = getDirContent($firstPath);
        foreach ($data as $da) {
            if (is_file($da)) {
                Yaf\Loader::import($firstPath . $da);
            } else {
                $thisPath = $firstPath . $da;
                $files = getDirContent($thisPath);
                foreach ($files as $file) {
                    Yaf\Loader::import($thisPath . '/' . $file);
                }
            }
        }
        $data = null;
    }


    /**
     * 加载crontab类
     */
    public function _initCrontabLoader()
    {
        $firstPath = APP_PATH . '/app/crontab/';
        $data = getDirContent($firstPath);
        foreach ($data as $da) {
            if (is_file($firstPath . $da)) {
                Yaf\Loader::import($firstPath . $da);
            } else {
                $thisPath = $firstPath . $da;
                $files = getDirContent($thisPath);
                foreach ($files as $file) {
                    Yaf\Loader::import($thisPath . '/' . $file);
                }
            }
        }
        $data = null;
    }

}
