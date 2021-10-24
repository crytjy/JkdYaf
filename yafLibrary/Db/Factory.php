<?php

namespace Db;

use Conf\JkdConf;

/**
 * Db工厂数据类
 */
class Factory
{

    /**
     * 创建连接
     *
     * @param $dbName
     * @return DbInterface|Mysql|false
     */
    static public function create($dbName)
    {
        $config = \Yaf\Registry::get('dbConf');
        $db = \Db\Mysql::getInstance($config[$dbName]);
        return ($db instanceof DbInterface) ? $db : false;
    }


    /**
     * 获取连接池
     *
     * @return DbInterface|MysqlPool|false
     */
    static public function getPool()
    {
        $db = MysqlPool::getInstance();
        return ($db instanceof DbInterface) ? $db : false;
    }

}