<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace app\task;

use Task\JkdTaskInterface;

class Test implements JkdTaskInterface
{
    public function handle($params)
    {
        \SeasLog::info('taskTest' . json_encode($params));
    }
}
