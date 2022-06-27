<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
include __DIR__ . '/bin/Jkd.php';
include __DIR__ . '/Server/Pool.php';

use Swoole\Process;

define('JKDYAF_VERSION', '2.3.0');

class HttpServer
{
    protected static $instance;

    protected static $daemonize;

    private $jkdYafConfig;

    private $ip;

    private $port;

    private function __construct()
    {
        // 获取JKdYaf配置文件
        $this->jkdYafConfig = parse_ini_file(CONF_PATH . '/jkdYaf.ini', true);

        $this->ip = $this->jkdYafConfig['common']['ip'] ?? '';
        $this->port = $this->jkdYafConfig['common']['port'] ?? '';
    }

    /**
     * @param string $argv2
     * @return null|HttpServer
     */
    public static function getInstance($argv2 = '')
    {
        $daemonize = ($argv2 ?? '') == '-d' ? true : false; // 守护进程  true|false

        if (empty(self::$instance) || ! (self::$instance instanceof HttpServer)) {
            self::$instance = new self();
            self::$daemonize = $daemonize;
        }

        return self::$instance;
    }

    /**
     * 启动.
     *
     * @return bool
     */
    public function start()
    {
        $pids = file_get_contents($this->jkdYafConfig['common']['pid_file']);
        if ($pids) {
            return \Jkd::echoStr('JkdYaf is running');
        }

        if (self::$daemonize) {
            \Jkd::echoStr('JkdYaf is running');
        } else {
            \Jkd::start($this->ip, $this->port, self::$daemonize);
        }

        $pool = new \Server\Pool($this->jkdYafConfig['common'], self::$daemonize);
        return $pool->start();
    }

    /**
     * 停止.
     *
     * @return bool
     */
    public function stop()
    {
        $pids = file_get_contents($this->jkdYafConfig['common']['pid_file']);
        if ($pids) {
            $pids = explode('|', $pids);
            foreach ($pids as $pid) {
                Process::kill($pid, SIGKILL);
            }
            file_put_contents($this->jkdYafConfig['common']['pid_file'], '');
            return \Jkd::echoStr('JkdYaf is stopped', 3);
        }

        return \Jkd::echoStr('JkdYaf can not stop', 3);
    }

    /**
     * 重启.
     */
    public function restart()
    {
        $this->stop();
        self::$daemonize = true;
        $this->start();
    }

    public function status()
    {
        $pids = file_get_contents($this->jkdYafConfig['common']['pid_file']);
        $pid = $pids ? (explode('|', $pids)[0] ?? 0) : 0;
        if ($pid && Process::kill($pid, PRIO_PROCESS)) {
            self::$daemonize = true;
            \Jkd::start($this->ip, $this->port, self::$daemonize);
            return true;
        }

        return \Jkd::echoStr('JkdYaf is not running', 2);
    }
}
