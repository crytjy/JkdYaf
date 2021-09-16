<?php
/**
 * Redis 操作类
 *
 * @author JKD
 * @date 2021年04月10日 16:38
 */

namespace Cache;

class Redis
{

    /**
     * Redis连接池实例
     */
    private $pool;

    /**
     * 是否归还了链接
     */
    private $returnStatus = false;


    /**
     * 选择连接池
     *
     * Redis constructor.
     */
    public function __construct()
    {
        $this->pool = \Pool\JkdRedisPool::run()->pop();
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


    public function get()
    {
        return $this->pool;
    }


    /**
     * 归还连接池
     *
     * @return mixed
     */
    public function put()
    {
        $this->returnStatus = true;
        return \Pool\JkdRedisPool::run()->free($this->pool);
    }

}