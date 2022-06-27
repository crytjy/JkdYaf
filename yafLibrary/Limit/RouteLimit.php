<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Limit;

use Cache\Redis;
use Route\JkdRouter;

class RouteLimit
{
    public const LIMIT_REQUEST_KEY = 'LIMIT_REQUEST_KEY:';

    /**
     * 上次路由数据--限制.
     *
     * @var array
     */
    public static $lastRouteLimit = [];

    /**
     * 限流
     */
    public static function limit(int $minute = 1, int $limit = 60)
    {
        $limitArray = [
            'time' => $minute * 60,
            'limit' => $limit,
        ];
        if (JkdRouter::$lastRouteUri) {
            JkdRouter::$action[JkdRouter::$lastRouteUri]['limit'] = $limitArray;
        } else {
            self::$lastRouteLimit = $limitArray;
        }
    }

    /**
     * 检查是否限流
     *
     * @param mixed $clientIp
     */
    public static function checkLimit(array $limitArr, $clientIp, string $route = ''): bool
    {
        $status = true;
        $limit = $limitArr['self'] ?: $limitArr['throttle'];
        $prefix = $limitArr['prefix'] ?? '';
        if ($limit) {
            $redisKey = self::LIMIT_REQUEST_KEY . $clientIp;
            if ($route && $limitArr['self']) {
                $redisKey .= ':' . $route;
            } else {
                $redisKey .= ':' . $prefix;
            }

            $redisPool = new Redis();
            $redis = $redisPool->get();
            if ($redis->EXISTS($redisKey)) {    // 存在判断是否限流
                $reqNum = $redis->get($redisKey);
                if ($reqNum < $limit['limit']) {
                    $redis->incr($redisKey);
                } else {
                    $status = false;
                }
            } else {    // 不存在，直接累加
                $redis->set($redisKey, 1, $limit['time']);
            }

            $redisPool->put();
        }
        return $status;
    }
}
