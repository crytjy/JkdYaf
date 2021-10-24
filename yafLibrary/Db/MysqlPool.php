<?php
/**
 * Mysql 操作类
 *
 * @author JKD
 * @date 2021年10月20日 23:40
 */

namespace Db;

use Pool\JkdMysqlPool;

class MysqlPool extends MysqlHandle
{

    private static $_instances;

    /**
     * 是否归还了链接
     */
    public $returnStatus = false;


    /**
     * 选择连接池
     *
     * Redis constructor.
     */
    public function __construct()
    {
        $this->_dbh = JkdMysqlPool::run()->pop();
    }


    /**
     * 利用析构函数，防止有漏掉没归还的连接，让其自动回收，减少不规范的开发者
     */
    public function __destruct()
    {
        if ($this->returnStatus === false) {
            $this->put();
        }
    }


    static public function getInstance()
    {
        self::$_instances = new MysqlPool();
        return self::$_instances;
    }


    public function get()
    {
        return $this->_dbh;
    }


    /**
     * 归还连接池
     */
    public function put()
    {
        $this->returnStatus = true;
        JkdMysqlPool::run()->free($this->_dbh);
    }

}