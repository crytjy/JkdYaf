<?php

use app\services\Api\Index as ApiIndex;

class IndexController extends BaseController
{

    protected $serviceIndex;
    public function init()
    {
        parent::init();
        $this->serviceIndex = new ApiIndex($this->JkdRequest);
    }

    public function indexAction()
    {
        \Response::Success('Hello API');
    }


    public function getAllUserAction()
    {
        return $this->serviceIndex->getAllUser();
    }

    public function getTokenAction()
    {
        return $this->serviceIndex->getToken();
    }

}
