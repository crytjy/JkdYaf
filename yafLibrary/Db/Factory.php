<?php

namespace Db;

/**
 * Db工厂数据类
 */
class Factory
{

    static public function create($config)
    {
        $db = \Db\Mysql::getInstance($config);

        return ($db instanceof DbInterface) ? $db : false;
    }

}