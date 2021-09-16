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
        $this->JkdRequest = Yaf\Registry::get('REQUEST_PARAMS');
    }

}
