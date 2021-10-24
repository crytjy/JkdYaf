<?php
namespace Com;

use Log\JkdLog;

class TestAop
{

    public function test1()
    {
        JkdLog::channel('aop', 'AOP1111');
    }

    public function test2()
    {
        JkdLog::channel('aop', 'AOP2222');
    }

    public function test3()
    {
        JkdLog::channel('aop', 'AOP3333');
    }

}