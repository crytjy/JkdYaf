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

use Route\JkdRoute;

class JkdMiddleware
{
    /**
     * @var JkdMiddleware
     */
    private static $instance;

    /**
     * Get the instance of JkdMiddleware.
     *
     * @return JkdMiddleware
     */
    public static function get()
    {
        if (! self::$instance) {
            self::$instance = new JkdMiddleware();
        }
        return self::$instance;
    }

    public function handle()
    {
        $middlewareArr = JkdRoute::get()->getRouteType('middleware');
        foreach ($middlewareArr as $type => $middleware) {
            foreach ($middleware as $middle) {
                if ($type == 'app') {
                    $className = 'app\middleware\\' . $middle;
                } else {
                    $className = 'Middleware\\' . $middle;
                }
                $thisMiddleware = new $className();
                $thisMiddleware->handle();
            }
        }
    }
}
