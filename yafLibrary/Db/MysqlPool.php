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

use Pool\JkdMysqlPool;

class MysqlPool extends MysqlHandle
{
    /**
     * 是否归还了链接.
     */
    public $returnStatus = false;

    private static $_instances;

    /**
     * 选择连接池.
     *
     * Redis constructor.
     */
    public function __construct()
    {
        $this->_dbh = JkdMysqlPool::run()->pop();
    }

    /**
     * 利用析构函数，防止有漏掉没归还的连接，让其自动回收，减少不规范的开发者.
     */
    public function __destruct()
    {
        if ($this->returnStatus === false) {
            $this->put();
        }
    }

    public static function getInstance()
    {
        self::$_instances = new MysqlPool();
        return self::$_instances;
    }

    public function get()
    {
        return $this->_dbh;
    }

    /**
     * 归还连接池.
     */
    public function put()
    {
        $this->returnStatus = true;
        JkdMysqlPool::run()->free($this->_dbh);
    }
}
