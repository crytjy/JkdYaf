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

use Aop\JkdAop;
use Jkd\JkdResponse;

class JkdRouteRegistrar
{
    private $routeList = [];

    private $routePrefixList = [];

    public function handle(): void
    {
        $this->createRoutes();

        \Yaf\Registry::set('JkdRouteList', $this->routeList);
        \Yaf\Registry::set('JkdRoutePrefixList', $this->routePrefixList);
        $this->routeList = [];
        $this->routePrefixList = [];
    }

    /**
     * Create routes.
     */
    private function createRoutes(): void
    {
        $routeIniConf = \Conf\JkdConf::get('route', false);
        $routeIniArr = $routeIniConf->toArray();

        foreach ($routeIniArr as $iniUri => $routeIni) {
            $throttle = [];
            if (isset($routeIni['throttle']) && $routeIni['throttle']) {
                $routeThrottle = explode(',', $routeIni['throttle']);
                if (count($routeThrottle) != 2) {
                    JkdResponse::Error('The route throttle is error');
                }
                $throttle = [
                    'time' => (int) trim($routeThrottle[0]) * 60,
                    'limit' => (int) trim($routeThrottle[1]),
                ];
            }

            $filePath = APP_PATH . '/routes/' . $iniUri . '.php';
            if (! file_exists($filePath)) {
                JkdResponse::Error('The Route Not Found: ' . $filePath);
            }
            include $filePath;
            $thisRouteList = JkdRouter::$action;

            JkdRouter::$action = [];
            $filePath = null;
            $this->routePrefixList[] = $routeIni['prefix'] ?: '';
            foreach ($thisRouteList as $uri => $routeLi) {
                $thisUri = ($routeIni['prefix'] ? '/' . $routeIni['prefix'] : '') . '/' . $uri;
                [$n, $controllerName, $actionName] = explode('/', $routeLi['action']);

                $this->routeList[$thisUri] = [
                    'method' => $routeLi['method'],
                    'action' => '/' . $routeIni['modules'] . $routeLi['action'],
                    'middleware' => $this->getMiddlewareList($this->getMiddleware($routeIni['middleware'], $routeLi['middleware'])),
                    'limit' => [
                        'self' => $routeLi['limit'] ?? [],
                        'throttle' => $throttle,
                        'prefix' => $routeIni['prefix'] ?? 'default',
                    ],
                    'aop' => JkdAop::get()->getAopParser($routeIni['modules'], $controllerName, $actionName),
                ];
            }
            $thisRouteList = null;
        }
    }

    /**
     * Get a middleware.
     */
    private function getMiddleware(string $routeMiddleware, array $middleware = []): array
    {
        $thisMiddlewareArr = $routeMiddleware ? explode(',', $routeMiddleware) : [];
        $thisMiddlewareArr = array_unique(array_merge($thisMiddlewareArr, $middleware));
        return array_map(function ($thisMiddleware) {
            return trim($thisMiddleware);
        }, $thisMiddlewareArr);
    }

    /**
     * Get middleware list.
     *
     * @return array|array[]
     */
    private function getMiddlewareList(array $middlewareList = []): array
    {
        $middleware = [
            'app' => [],
            'com' => [],
        ];
        foreach ($middlewareList as $middlewareClass) {
            if (file_exists(APP_PATH . '/app/middleware/' . $middlewareClass . '.php')) {
                $middleware['app'][] = $middlewareClass;
            } elseif (file_exists(LIB_PATH . '/Middleware/' . $middlewareClass . '.php')) {
                $middleware['com'][] = $middlewareClass;
            } else {
                JkdResponse::Error('The Middleware Not Found: ' . $middlewareClass);
            }
        }

        return $middleware;
    }
}
