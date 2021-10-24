<?php
/**
 * @name JkdRouter
 * @author JKD
 * @date 2021年10月24日 02:50
 */

namespace Route;

use Jkd\JkdResponse;

class JkdRouter
{
    private $routeList = [];
    private $routePrefixList = [];

    public function handle()
    {
        $this->getRoutes();

        \Yaf\Registry::set('JkdRouteList', $this->routeList);
        \Yaf\Registry::set('JkdRoutePrefixList', $this->routePrefixList);
    }


    /**
     * 获取路由
     *
     * @return array
     */
    private function getRoutes()
    {
        $routeIniConf = \Conf\JkdConf::get('route', false);
        $middlewareConfig = $routeIniConf ? $routeIniConf['middlewareGroup'] : [];
        $routeConfig = $routeIniConf ? $routeIniConf['route'] : [];

        $routeIniConf = null;
        foreach ($routeConfig as $route => $routeConf) {
            $this->routePrefixList[] = $route;
            if (!file_exists(APP_PATH . '/conf/routes/' . $route . '.ini')) {
                JkdResponse::Error('The Route Not Found: ' . $route . '.ini');
            }
            $thisRouteConf = \Conf\JkdConf::get('routes/' . $route, false);

            foreach ($thisRouteConf as $uri => $thisRoute) {
                if (isset($middlewareConfig[$uri]) && $middlewareConfig[$uri]) {
                    $thisMiddlewareRoutes = $thisRoute;
                    $thisRoute = null;
                    $thisMiddlewareList = $this->getMiddleware($routeConf['middleware'] ?? '', $middlewareConfig[$uri]);
                    foreach ($thisMiddlewareRoutes as $url => $thisMiddlewareRoute) {
                        foreach ($thisMiddlewareRoute as $method => $route) {
                            $this->addRoute('/' . $routeConf['prefix'] . '/' . $url, $method, '/' . $routeConf['modules'] . $route, $thisMiddlewareList);
                        }
                    }
                } else {
                    $thisMiddlewareList = $this->getMiddleware($routeConf['middleware'] ?? '');
                    foreach ($thisRoute as $method => $route) {
                        $this->addRoute('/' . $routeConf['prefix'] . '/' . $uri, $method, '/' . $routeConf['modules'] . $route, $thisMiddlewareList);
                    }
                }
            }
            $thisRouteConf = null;
        }
        $middlewareConfig = null;
        $routeConfig = null;
    }


    /**
     * 获取中间件
     *
     * @param $routeMiddleware
     * @param string $middleware
     * @return array|string[]
     */
    private function getMiddleware($routeMiddleware, $middleware = '')
    {
        if ($middleware) {
            $routeMiddleware .= ',' . $middleware;
        }
        $thisMiddlewareArr = $routeMiddleware ? explode(',', $routeMiddleware) : [];
        $thisMiddlewareList = array_map(function ($thisMiddleware) {
            return trim($thisMiddleware);
        }, $thisMiddlewareArr);

        return $thisMiddlewareList;
    }


    /**
     * 添加路由
     *
     * @param string $route
     * @param string $method
     * @param string $action
     * @param array $middlewareList
     */
    private function addRoute(string $route, string $method, string $action, array $middlewareList)
    {
        $middleware = [
            'app' => [],
            'com' => []
        ];
        foreach ($middlewareList as $middlewareClass) {
            if ($middlewareClass) {
                if (file_exists(APP_PATH . '/app/middleware/' . $middlewareClass . '.php')) {
                    $middleware['app'][] = $middlewareClass;
                } elseif (file_exists(LIB_PATH . '/Middleware/' . $middlewareClass . '.php')) {
                    $middleware['com'][] = $middlewareClass;
                } else {
                    JkdResponse::Error('The Middleware Not Found: ' . $middlewareClass);
                }
            }
        }

        $this->routeList[$route] = [
            'method' => $method,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

}