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
        Yaf\Loader::import(APP_PATH . '/app/function/helper.php');
        Yaf\Loader::import(APP_PATH . '/app/function/common.php');

        //加载模型父类
        Yaf\Loader::import(APP_PATH . '/app/models/Base.php');
    }

    public function _initConfig()
    {
        //把配置保存起来
        $arrConfig = Yaf\Application::app()->getConfig();
        Yaf\Registry::set('config', $arrConfig);

        //保存route
        $routeConfig = \JkdConf::get('route');
        Yaf\Registry::set('routeConf', $routeConfig);

        //关闭视图
        Yaf\Dispatcher::getInstance()->autoRender(FALSE);
    }

    // 载入redis
    public function _initRedis()
    {
//        $arrConfig = Yaf\Registry::get('config')->redis;
//        $option = ['host' => $arrConfig->host, 'port' => $arrConfig->port, 'password' => $arrConfig->password];
//        $redis = new \Cache\Redis($option);
//        Yaf\Registry::set('redis', $redis);
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

}
