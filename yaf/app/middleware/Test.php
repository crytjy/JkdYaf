<?php

namespace app\middleware;

use Log\JkdLog;

class Test
{

    public function handle()
    {
        JkdLog::channel('middleware', 'middleware', 'test');
    }


}