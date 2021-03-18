<?php

namespace Db;

/**
 * Db工厂数据类
 */
class Factory
{

    static public function create($config)
    {
//        $config = \JkdConf::get('db');
        $db = \Db\Mysql::getInstance($config);

        return ($db instanceof DbInterface) ? $db : false;
    }


    static public function runDbCo($sql, $type = 'getRow')
    {

        \JkdLog::info([1]);
        \Co::set(['hook_flags' => SWOOLE_HOOK_TCP]);

        \JkdLog::info([2]);
        $http = new \Swoole\Http\Server("0.0.0.0", 6666);
        $http->set(['enable_coroutine' => true]);
        \JkdLog::info([3]);
        $http->on('request', function ($request, $response) use ($sql, $type) {
            \JkdLog::info([4]);
            $config = \JkdConf::get('db');
            $db = \Db\Mysql::getInstance($config);
            \JkdLog::info([5]);
            $res = $db->$type($sql);
            \JkdLog::info($res);
        });
        \JkdLog::info([7]);
        $http->start();
        \JkdLog::info([8]);
    }
}