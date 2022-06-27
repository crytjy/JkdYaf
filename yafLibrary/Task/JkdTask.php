<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Task;

use Swoole\Timer;

class JkdTask
{
    /**
     * 分发一个任务
     *
     * @param $taskClass
     */
    public static function dispatch($taskClass, array $data = []): int
    {
        $clas = new $taskClass();
        return Timer::after(1, function () use ($clas, $data) {
            $clas->handle($data);
        });
    }

    /**
     * 延迟分发任务
     *
     * @param $taskClass
     */
    public static function delay($taskClass, int $ms, array $data = []): int
    {
        $clas = new $taskClass();
        return Timer::after($ms, function () use ($clas, $data) {
            $clas->handle($data);
        });
    }

    /**
     * 定时任务
     *
     * @param $taskClass
     */
    public static function tick($taskClass, int $ms, array $data = []): int
    {
        $clas = new $taskClass();
        return Timer::tick($ms, function () use ($clas, $data) {
            $clas->handle($data);
        });
    }

    /**
     * 清除任务
     */
    public static function clear(int $timerId): bool
    {
        return Timer::clear($timerId);
    }
}
