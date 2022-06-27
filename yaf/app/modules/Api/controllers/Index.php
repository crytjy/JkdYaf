<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
class IndexController extends \Jkd\JkdBaseController
{
    #[
        Describe('Index'),
        AopBefore(\Com\TestAop::class, 'test1'),
        AopBefore('Com\\TestAop', 'test2'),
        AopAfter('Com\TestAop', 'test2'),
        AopAround(\Com\TestAop::class, 'test3')
    ]
    public function indexAction()
    {
        return $this->JkdService->index();
    }
}
