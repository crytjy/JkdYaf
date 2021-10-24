<?php

namespace app\middleware;

use Log\JkdLog;

class Test2
{

    public function handle()
    {
        JkdLog::channel('middleware', 'middleware', 'test2222');
    }


}