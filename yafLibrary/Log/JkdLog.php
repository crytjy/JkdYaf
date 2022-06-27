<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Log;

use Conf\JkdConf;

class JkdLog
{
    public static function handle()
    {
        $logConf = JkdConf::get('log');
        $basePath = $logConf->basePath ?? '';
        $logger = $logConf->logger ?? '';

        if ($basePath) {
            \SeasLog::setBasePath($basePath);
        }

        if ($logger) {
            \SeasLog::setLogger($logger);
        }
    }
}
