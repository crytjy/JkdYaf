<?php

/**
 * Yac是无锁的、共享内存的Cache
 */

namespace Yac;

class YacCache
{
    private static $_ttlMaxTime = 864000;  //86400*10 为防止永久贮存及保存时间过久造成内存消耗严重导致数据被踢出
    public $yac;

    public function __construct($prefix = '')
    {
        $this->yac = new \Yac($prefix);
    }


    /**
     * 添加单个
     *
     * @param string $keys
     * @param $value
     * @param int $ttl
     * @return mixed
     */
    public function add($key, $value = '', $ttl = -1)
    {
        if (is_array($key)) {
            return false;
        } else {
            return $this->yac->add($key, $value, self::getTtl($ttl));
        }
    }


    /**
     * 添加多个
     *
     * @param array $keys
     * @param int $ttl
     * @return mixed
     */
    public function adds($keys, $ttl = -1)
    {
        if (is_array($keys)) {
            return $this->yac->add($keys, self::getTtl($ttl));
        } else {
            return false;
        }
    }


    /**
     * 设置
     *
     * @param $key
     * @param $value
     * @param int $ttl
     * @return mixed
     */
    public function set($key, $value, $ttl = -1)
    {
        return $this->yac->set($key, $value, self::getTtl($ttl));
    }


    /**
     * 获取
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->yac->get($key);
    }


    /**
     * 删除
     * 从缓存中删除存储的变量。如果指定了delay，则该值将在$delay秒后删除。
     *
     * @param array|string $keys
     * @param int $delay
     * @return mixed
     */
    public function delete($keys, $delay = 0)
    {
        return $this->yac->delete($keys, $delay);
    }


    /**
     * 清楚所有
     *
     * 立即使所有现有项目失效。
     * 它实际上并没有释放任何资源，它仅将所有项目标记为无效。
     *
     * @return mixed
     */
    public function flush()
    {
        return $this->yac->flush();
    }


    /**
     * 查看信息
     *
     * @param $key
     * @return mixed
     */
    public function info()
    {
        return $this->yac->info();
    }


    /**
     * 获取所有Key信息
     *
     * @param $key
     * @return mixed
     */
    public function dump()
    {
        return $this->yac->dump();
    }


    /**
     * 获取所有Key
     *
     * @param $key
     * @return mixed
     */
    public function getAllKeys()
    {
        $data = $this->dump();
        if ($data) {
            $keys = array_column($data, 'key');
        }

        return $keys ?? [];
    }


    /**
     * 验证储存时间
     * 超过最大时间，则返回最大时间
     *
     * @param int $ttl
     * @return int
     */
    private static function getTtl($ttl = -1)
    {
        if ($ttl < 0 || $ttl > self::$_ttlMaxTime) {
            $ttl = self::$_ttlMaxTime;
        }

        return $ttl;
    }

}
