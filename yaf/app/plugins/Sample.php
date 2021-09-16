<?php

/**
 * @name SamplePlugin
 * @desc Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author root
 */
class SamplePlugin extends Yaf\Plugin_Abstract
{

    /**
     * 在路由之前执行,这个钩子里，你可以做url重写等功能
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return mixed|void
     */
    public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
         \JkdRoutes::checkRoute();
    }


    /**
     * 路由结束之后触发
     * 路由完成后，在这个钩子里，你可以做登陆检测等功能
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return mixed|void
     */
    public function routerShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
    }

    /**
     * 分发循环开始之前被触发
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return mixed|void
     */
    public function dispatchLoopStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
    }


    /**
     * 分发之前触发
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return mixed|void
     */
    public function preDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
    }


    /**
     * 分发结束之后触发
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return mixed|void
     */
    public function postDispatch(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
    }


    /**
     *    分发循环结束之后触发
     *
     * @param \Yaf\Request_Abstract $request
     * @param \Yaf\Response_Abstract $response
     * @return mixed|void
     */
    public function dispatchLoopShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
    }
}
