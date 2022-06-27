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

use Jkd\JkdResponse;
use Limit\RouteLimit;
use Middleware\RouteMiddleware;

class JkdRouter
{
    public static $action = [];

    /**
     * 上次路由限流
     *
     * @var string
     */
    public static $lastRouteUri = '';

    /**
     * All of the verbs supported by the router.
     *
     * @var string[]
     */
    private static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public function __construct()
    {
        self::$lastRouteUri = '';
    }

    /**
     * Register a new GET route with the router.
     *
     * @return bool
     */
    public function get(string $uri, array $action = [])
    {
        return $this->addRoute(['GET', 'HEAD'], $uri, $action);
    }

    /**
     * Register a new POST route with the router.
     *
     * @return bool
     */
    public function post(string $uri, array $action = [])
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @return bool
     */
    public function put(string $uri, array $action = [])
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     *
     * @return bool
     */
    public function patch(string $uri, array $action = [])
    {
        return $this->addRoute('PATCH', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @return bool
     */
    public function delete(string $uri, array $action = [])
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Register a new OPTIONS route with the router.
     *
     * @return bool
     */
    public function options(string $uri, array $action = [])
    {
        return $this->addRoute('OPTIONS', $uri, $action);
    }

    /**
     * Register a new route responding to all verbs.
     *
     * @return bool
     */
    public function any(string $uri, array $action = [])
    {
        return $this->addRoute(self::$verbs, $uri, $action);
    }

    public function clear()
    {
        self::$action = [];
    }

    /**
     * 限流
     *
     * @return $this
     */
    public function limit(int $minute = 1, int $limit = 60): object|array
    {
        RouteLimit::limit($minute, $limit);
        return $this;
    }

    /**
     * Register middleware with the router.
     */
    public function middleware(array|string $middleware = null): object|array
    {
        RouteMiddleware::middleware($middleware);
        return $this;
    }

    /**
     * Add a route to the underlying route.
     *
     * @return bool
     */
    private function addRoute(string|array $method, string $uri, array $action): object
    {
        if (! is_array($action)) {
            return false;
        }

        if (! is_array($method)) {
            $method = (array) $method;
        }

        $class = $action[0] ?? '';
        $func = $action[1] ?? '';

        if (! $class || ! $func) {
            return false;
        }

        $classFunc = $this->getAction($class, $func);

        self::$action[$uri] = [
            'method' => $method,
            'action' => $classFunc,
            'middleware' => RouteMiddleware::$middlewareArr ?: (self::$action[$uri]['middleware'] ?? []),
            'limit' => RouteLimit::$lastRouteLimit ?? [],
        ];

        self::$lastRouteUri = $uri;
        RouteLimit::$lastRouteLimit = [];
        RouteMiddleware::$middlewareArr = [];

        return $this;
    }

    /**
     * Get Class Function Route.
     */
    private function getAction(string $class, string $func): string
    {
        $class = explode('Controller', $class)[0] ?? '';
        if (! $class) {
            JkdResponse::Error('The Action Not Found: ' . $class);
        }
        return '/' . $class . '/' . $func;
    }
}
