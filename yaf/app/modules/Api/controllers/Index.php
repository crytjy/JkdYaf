<?php
/**
 * @author JKD
 * @date 2021年08月03日 23:50
 */

class IndexController extends \Jkd\JkdBaseController
{

    /**
     * Index
     *
     * @AopBefore(Com\TestAop, test1)
     * @AopAfter(Com\TestAop, test2)
     * @AopAround(Com\TestAop, test3)
     *
     * @return mixed
     */
    public function indexAction()
    {
        return $this->JkdService->index();
    }
}
