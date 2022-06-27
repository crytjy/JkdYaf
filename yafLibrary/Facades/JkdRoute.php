<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Facades;

/**
 * @method static \Route\JkdRouter middleware(array|string $middleware = null)
 * @method static \Route\JkdRouter get(string $uri, array $action = [])
 * @method static \Route\JkdRouter post(string $uri, array $action = [])
 * @method static \Route\JkdRouter put(string $uri, array $action = [])
 * @method static \Route\JkdRouter patch(string $uri, array $action = [])
 * @method static \Route\JkdRouter delete(string $uri, array $action = [])
 * @method static \Route\JkdRouter options(string $uri, array $action = [])
 * @method static \Route\JkdRouter any(string $uri, array $action = [])
 * @method static \Route\JkdRouter limit(int $minute = 1, int $limit = 60)
 */
class JkdRoute extends JkdFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return '\Route\JkdRouter';
    }
}
