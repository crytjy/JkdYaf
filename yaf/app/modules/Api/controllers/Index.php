<?php
/**
 * @author JKD
 * @date 2021年08月03日 23:50
 */

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
        return $this->serviceIndex->index();
    }
}
