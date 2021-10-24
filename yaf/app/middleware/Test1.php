<?php

namespace app\middleware;

use Log\JkdLog;

class Test1
{

    public function handle()
    {
        JkdLog::channel('middleware', 'middleware', 'test1111');
    }


}