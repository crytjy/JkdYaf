<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Db;

use Conf\JkdConf;

/**
 * Db工厂数据类.
 */
class Factory
{
    /**
     * 创建连接.
     *
     * @param $dbName
     * @return DbInterface|false|Mysql
     */
    public static function create($dbName)
    {
        $config = JkdConf::get('db');
        $db = \Db\Mysql::getInstance($config[$dbName]);
        return ($db instanceof DbInterface) ? $db : false;
    }

    /**
     * 获取连接池.
     *
     * @return DbInterface|false|MysqlPool
     */
    public static function getPool()
    {
        $db = MysqlPool::getInstance();
        return ($db instanceof DbInterface) ? $db : false;
    }
}
