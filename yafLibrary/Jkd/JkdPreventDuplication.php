<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Jkd;

class JkdPreventDuplication
{
    /**
     * 检查是否通过.
     *
     * @param mixed $type
     * @param mixed $ttl
     * @return mixed
     */
    public static function check($type, $ttl = 3)
    {
        $redisPool = new \Cache\Redis();
        $redis = $redisPool->get();

        $key = 'PREVENT-DUPLICATION-' . $type;
        $rs = $redis->set($key, 1, ['nx', 'ex' => $ttl]);

        $redisPool->put();
        return $rs;
    }
}
