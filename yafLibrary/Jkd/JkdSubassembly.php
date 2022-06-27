<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Jkd;

use Cron\JkdCron;
use Log\JkdLog;
use Pool\JkdMysqlPool;
use Pool\JkdRedisPool;

class JkdSubassembly
{
    public function handle($masterPid, $workerId, $config)
    {
        // 启动Redis连接池
        $this->startRedis($workerId);

        // 启动Mysql连接池
        $this->startMysql($workerId);

        if ($workerId == 0) {
            // 开启定时器
            $this->startCron($masterPid, $config);
        }

        // 初始化日志配置
        JkdLog::handle();
    }

    /**
     * 打开Redis连接池.
     *
     * @param $workerId
     */
    private function startRedis($workerId)
    {
        // 启动数据库连接池
        JkdRedisPool::run()->init();
        // 启动连接池检测定时器
        JkdRedisPool::run()->timingRecovery($workerId);
    }

    /**
     * 打开MYsql连接池.
     *
     * @param $workerId
     */
    private function startMysql($workerId)
    {
        // 启动数据库连接池
        JkdMysqlPool::run()->init();
        // 启动连接池检测定时器
        JkdMysqlPool::run()->timingRecovery($workerId);
    }

    /**
     * 开启定时任务
     * @param mixed $masterPid
     * @param mixed $config
     */
    private function startCron($masterPid, $config)
    {
        JkdCron::start($masterPid, $config['timer_pid_file']);
    }
}
