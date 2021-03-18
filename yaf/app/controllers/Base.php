<?php

/**
 * API 控制器
 * Class BaseController
 */
class BaseController extends \Yaf\Controller_Abstract
{

    protected $JkdRequest;

    public function init()
    {
//        $this->JkdRequest = \HttpServer::$params;
        $this->JkdRequest = Yaf\Registry::get('REQUEST_PARAMS');

        // 加载该控制器对应的service层
        $data = Yaf\Dispatcher::getInstance()->getRequest();
        $uri = $data->getModuleName() . '/' . $data->getControllerName();
        \Common\Service::loaderService($uri);
    }

}
