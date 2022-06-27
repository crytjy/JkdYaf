<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Route;

class JkdRoute
{
    /**
     * @var JkdRoute
     */
    private static $instance;

    private static $routeList;

    /**
     * Get the instance of JkdRoute.
     *
     * @return JkdRoute
     */
    public static function get()
    {
        if (! self::$instance) {
            self::$instance = new JkdRoute();
            self::$routeList = \Yaf\Registry::get('JkdRouteList');
        }
        return self::$instance;
    }

    public function getRoutePrefixs(): array
    {
        return \Yaf\Registry::get('JkdRoutePrefixList');
    }

    public function getRouteList(): array
    {
        return self::$routeList;
    }

    /**
     * 获取路由信息.
     */
    public function getRoute(string $uri): array
    {
        return self::$routeList[$uri] ?? [];
    }

    /**
     * 检查路由是否存在.
     */
    public function checkRouteExist(string $uri): bool
    {
        return isset(self::$routeList[$uri]) ? true : false;
    }

    /**
     * 获取路由值.
     *
     * @param $key
     */
    public function getRouteType($key): array
    {
        $route = $this->getRouteUri();
        return self::$routeList[$route][$key] ?? [];
    }

    /**
     * 客户端请求的路由.
     *
     * @return mixed|string
     */
    public function getRouteUri()
    {
        $serverData = getJkdYafParams('JKDYAF_REQ')->server;
        return $serverData['request_uri'] ?? '';  // 客户端请求的路由
    }
}
