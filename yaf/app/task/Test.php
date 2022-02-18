<?php

namespace app\task;

use Log\JkdLog;
use Task\JkdTaskInterface;

class Test implements JkdTaskInterface
{
    public function handle($params)
    {
        JkdLog::info('ssss', $params);
    }
}