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

abstract class JkdFacade
{
    /**
     * Handle dynamic, static calls to the object.
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $args)
    {
        $instance = static::getFacadeRoot();

        if (! $instance) {
            throw new \Exception('A facade root has not been set.');
        }

        return $instance->{$method}(...$args);
    }

    /**
     * Get the root object behind the facade.
     *
     * @return mixed
     */
    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * Resolve the facade root instance from the container.
     *
     * @param object|string $name
     * @return mixed
     */
    protected static function resolveFacadeInstance($name)
    {
        return new $name();
    }
}
