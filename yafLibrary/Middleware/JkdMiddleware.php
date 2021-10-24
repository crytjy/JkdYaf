<?php
/**
 * @name JkdMiddleware
 * @deprecated 中间价
 * @author JKD
 * @date 2021年10月24日 03:12
 */

namespace Middleware;

use Log\JkdLog;
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
        if (!self::$instance) {
            self::$instance = new JkdMiddleware();
        }
        return self::$instance;
    }


    public function handle()
    {
        $middlewareArr = JkdRoute::get()->getRouteMiddleware();
        JkdLog::info('ss', $middlewareArr);

        foreach ($middlewareArr as $type => $middleware) {
            JkdLog::info($type, $middleware);
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