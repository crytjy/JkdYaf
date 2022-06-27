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

class JkdSqlLog
{
    public static function handle(array $params)
    {
        $runtime = $params['runtime'];
        $sqlStr = $params['sqlStr'];
        $params = $params['values'];

        \SeasLog::info('Runtime: ' . $runtime . ' - SQL:' . json_encode([
            'sqlStr' => $sqlStr,
            'values' => $params,
        ]), [], 'sqlLog');
    }
}
