<?php
/**
 * @author JKD
 * @date 2021年08月03日 23:50
 */
namespace app\services\Api;

use Jkd\JkdBaseService;
use Jkd\JkdResponse;
use Pool\JkdMysqlPool;
use Route\JkdRouter;

class Index extends JkdBaseService
{

    /**
     * Index
     *
     * @return bool
     */
    public function index()
    {
//        $router = new JkdRouter();
//        $list = $router->handle();


//        $test = new \TestModel();
//        $list = $test->all();


//        $test = new \TestModel();
//        $list1 = $test->get();


        return JkdResponse::Success($this->JkdRequest ?: 'Hello JkdYaf !');
//        return JkdResponse::Success($list ?? -1);
    }
}