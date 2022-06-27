<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Middleware;

use Route\JkdRouter;

class RouteMiddleware
{
    public static $middlewareArr = [];

    public static function middleware(array|string $middleware = null)
    {
        if (! is_array($middleware)) {
            $middleware = func_get_args();
        }

        if (JkdRouter::$lastRouteUri) {
            JkdRouter::$action[JkdRouter::$lastRouteUri]['middleware'] = $middleware ?? [];
        } else {
            self::$middlewareArr = $middleware ?? [];
        }
    }
}
