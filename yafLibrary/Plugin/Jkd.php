<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Plugin;

use Aop\JkdAop;
use Middleware\JkdMiddleware;

class JkdPlugin extends \Yaf\Plugin_Abstract
{
    /**
     * 在路由之前执行,这个钩子里，你可以做url重写等功能.
     *
     * @return mixed|void
     */
    public function routerStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
        if (checkIoStatus('middlewareStatus')) {
            JkdMiddleware::get()->handle(); // 启动中间件
        }
    }

    /**
     * 路由结束之后触发
     * 路由完成后，在这个钩子里，你可以做登陆检测等功能.
     *
     * @return mixed|void
     */
    public function routerShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
        if (checkIoStatus('aopStatus')) {
            JkdAop::get()->runAop('AopBefore'); // 启动AOP
            JkdAop::get()->runAop('AopAround'); // 启动AOP
        }
    }

    /**
     * 分发循环开始之前被触发.
     *
     * @return mixed|void
     */
    public function dispatchLoopStartup(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
    }

    /**
     * 分发之前触发.
     *
     * @return mixed|void
     */
    public function preDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
    }

    /**
     * 分发结束之后触发.
     *
     * @return mixed|void
     */
    public function postDispatch(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
        if (checkIoStatus('aopStatus')) {
            JkdAop::get()->runAop('AopAfter'); // 启动AOP
            JkdAop::get()->runAop('AopAround'); // 启动AOP
        }
    }

    /**
     *    分发循环结束之后触发.
     *
     * @return mixed|void
     */
    public function dispatchLoopShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
    }

    public function preResponse(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response)
    {
    }
}
