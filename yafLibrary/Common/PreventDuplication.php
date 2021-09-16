<?php
/**
 * 防止重复请求
 *
 * Class PreventDuplication
 */

namespace Common;

class PreventDuplication
{

    /**
     * 检查是否通过
     *
     * @return mixed
     */
    public static function check($type, $ttl = 3)
    {
        $redisPool = new \Cache\Redis();
        $redis = $redisPool->get();

        $key = 'PREVENTDUPLICATION' . $type;
        $rs = $redis->set($key, 1, ['nx', 'ex' => $ttl]);

        return $rs;
    }

}