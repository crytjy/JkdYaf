<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace app\crontab;

class Test
{
    public function test()
    {
        \SeasLog::info('定时任务', ['test'], 'crontab');
    }
}
