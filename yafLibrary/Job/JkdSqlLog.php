<?php
/**
 * 储存Sql操作记录
 */

namespace Job;

class JkdSqlLog
{

    public static function handle($params)
    {
        $sqlStr = $params['sqlStr'];
        $params = $params['values'];

        \Log\JkdLog::channel('sqlLog', 'SQL：', [
            'sqlStr' => $sqlStr,
            'values' => $params
        ]);
    }

}