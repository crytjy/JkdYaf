<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Job;

class JkdSysLog
{
    public static function handle($params)
    {
        $runtime = $params['runtime'];
        $route = $params['route'];
        $result = $params['result'];
        $params = $params['params'];

        \SeasLog::info('runtime：' . changeReqTime($runtime) . '-' . json_encode([
            'route' => $route,
            'Request' => $params,
            'Response' => $result,
        ]), [], 'sysReq');
    }
}
