<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Com;

class TestAop
{
    public function test1()
    {
        \SeasLog::info('AOP1111', [], 'aop');
    }

    public function test2()
    {
        \SeasLog::info('AOP2222', [], 'aop');
    }

    public function test3()
    {
        \SeasLog::info('AOP3333', [], 'aop');
    }
}
