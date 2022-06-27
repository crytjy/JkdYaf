<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Server;

include 'Http.php';
include LIB_PATH . 'UnAutoLoader/JkdAtomic.php';

use Swoole\Process;
use UnAutoLoader\JkdAtomic;

class Pool
{
    use JkdAtomic;

    protected $jkdYafConfig;

    protected $daemonize;

    public function __construct(array $jkdYafConfig, bool $daemonize = false)
    {
        $this->jkdYafConfig = $jkdYafConfig;
        $this->daemonize = $daemonize;
    }

    public function initFile()
    {
        file_put_contents($this->jkdYafConfig['pid_file'], '');
        file_put_contents($this->jkdYafConfig['worker_pid_file'], '');
        file_put_contents($this->jkdYafConfig['tasker_pid_file'], '');
        // Redis连接数
        $path = APP_PATH . '/runtime/pool/redis_pool_num.count';
        file_put_contents($path, '{}');

        // Mysql连接数
        $path = APP_PATH . '/runtime/pool/mysql_pool_num.count';
        file_put_contents($path, '{}');
    }

    public function start()
    {
        $this->initFile();

        $this->startAtomic();

        $pool = new Process\Pool($this->jkdYafConfig['worker_num'], SWOOLE_IPC_NONE, 0, true);
        swoole_set_process_name($this->jkdYafConfig['manager_process_name']);
        if ($this->daemonize) {
            Process::daemon();
            file_put_contents($this->jkdYafConfig['pid_file'], posix_getpid());
        }
        $pool->on('WorkerStart', [$this, 'onWorkerStart']);
        $pool->on('WorkerStop', [$this, 'onWorkerStop']);
        $pool->start();
        return true;
    }

    public function onWorkerStart(Process\Pool $pool, $workerId)
    {
        $processId = $pool->master_pid . ':' . $workerId;
        $processName = sprintf($this->jkdYafConfig['event_worker_process_name'], $processId);
        swoole_set_process_name($processName);
        if (is_file($this->jkdYafConfig['pid_file']) && $this->daemonize) {
            file_put_contents($this->jkdYafConfig['pid_file'], '|' . posix_getpid(), FILE_APPEND);
        }

        $http = new Http($this->jkdYafConfig, $this->daemonize);
        $http->start($pool->master_pid, $workerId);
    }

    public function onWorkerStop(Process\Pool $pool, $workerId)
    {
        echo "[Worker #{$workerId}] WorkerStop\n";
    }
}
