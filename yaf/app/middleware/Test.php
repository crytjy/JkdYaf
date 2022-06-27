<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace app\middleware;

class Test
{
    public function handle()
    {
        \SeasLog::info('test', [], 'middleware');
    }
}
