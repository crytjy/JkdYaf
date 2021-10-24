<?php

include __DIR__ . "/bin/Jkd.php";

use Swoole\Coroutine\Http\Server;
use Swoole\Process;

define('JKDYAF_VERSION', '2.1.3');

class HttpServer
{
    private $jkdYafConfig;
    private $app;

    protected static $runStatus = true;
    protected static $instance = null;
    protected static $daemonize = null;

    private $ip;
    private $port;
    private $appName;

    private function __construct()
    {
        // 获取JKdYaf配置文件
        $this->jkdYafConfig = parse_ini_file(CONF_PATH . "/jkdYaf.ini", true);

        $this->ip = $this->jkdYafConfig['common']['ip'] ?? '';
        $this->port = $this->jkdYafConfig['common']['port'] ?? '';
        $this->appName = $this->jkdYafConfig['common']['app_name'] ?? '';
    }


    /**
     * @param string $argv2
     * @return HttpServer|null
     */
    public static function getInstance($argv2 = '')
    {
        $daemonize = ($argv2 ?? '') == '-d' ? true : false; //守护进程  true|false

        if (empty(self::$instance) || !(self::$instance instanceof HttpServer)) {
            self::$instance = new self();
            self::$daemonize = $daemonize;
            self::$runStatus = false;
        }

        return self::$instance;
    }


    /**
     * 启动
     */
    public function start()
    {
        exec('ps -ef|grep ' . $this->appName, $res);
        $resCount = $res ? count($res) : 0;

        if ($resCount > 3) {
            return \Jkd::isRunning('JkdYaf is running');
        } else {
            \Jkd::start($this->ip, $this->port, self::$daemonize);
            return $this->onManagerStart();
        }
    }


    public function onManagerStart()
    {
        // 清空初始化文件
        file_put_contents($this->jkdYafConfig['common']['timer_pid_file'], '');

        if (self::$daemonize) {
            Process::daemon();
        }
        $pool = new Process\Pool($this->jkdYafConfig['server']['worker_num']);
        swoole_set_process_name($this->jkdYafConfig['common']['manager_process_name']);
        $pool->set(['enable_coroutine' => true]);
        $pool->on('WorkerStart', [$this, 'onWorkerStart']);
        $pool->on('WorkerStop', [$this, 'onWorkerStop']);
        $pool->start();

        return true;
    }


    public function onWorkerStart(Process\Pool $pool, $workerId)
    {
        // 清空初始化文件
        file_put_contents($this->jkdYafConfig['common']['worker_pid_file'], '');
        file_put_contents($this->jkdYafConfig['common']['tasker_pid_file'], '');
        // 初始化连接池日志文件
        $this->createPoolLog();

        $processId = $pool->master_pid . ':' . $workerId;
        if ($workerId >= $this->jkdYafConfig['server']['worker_num']) {
            $processName = sprintf($this->jkdYafConfig['common']['event_tasker_process_name'], $processId);
            if (is_file($this->jkdYafConfig['common']['tasker_pid_file'])) {
                file_put_contents($this->jkdYafConfig['common']['tasker_pid_file'], $pool->master_pid . ':' . $workerId . '|', FILE_APPEND);
            }
        } else {
            $processName = sprintf($this->jkdYafConfig['common']['event_worker_process_name'], $processId);
            if (is_file($this->jkdYafConfig['common']['worker_pid_file'])) {
                file_put_contents($this->jkdYafConfig['common']['worker_pid_file'], $pool->master_pid . ':' . $workerId . '|', FILE_APPEND);
            }
        }
        swoole_set_process_name($processName);

        $server = new Server($this->ip, $this->port, false, true);
        //实例化yaf
        $this->app = new Yaf\Application(APP_PATH . "/conf/app.ini");
        $this->app->bootstrap();

        // 启动Redis连接池
        $this->startRedis($workerId);
        // 启动Mysql连接池
        $this->startMysql($workerId);

        //开启定时器
        if ($workerId == 0) {
            $this->startCron($pool->master_pid);
        }
//        捕获异常
//        set_exception_handler(function (Exception $e) {
//            \JkdLog::error($e);
//        });

//        捕获错误
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            \Log\JkdLog::error([
                'Code:' => $errno,
                'Msg:' => $errstr,
                'File:' => $errfile,
                'Line:' => $errline
            ]);
        }, E_ALL);

        $server->handle('/api/', [$this, 'onRequest']);
        $server->handle('/admin/', [$this, 'onRequest']);

        \Log\JkdLog::channel('memory', '初始化', memory_get_usage());
        $server->start();
    }


    public function onWorkerStop(Process\Pool $pool, $workerId)
    {
        echo("[Worker #{$workerId}] WorkerStop\n");
    }


    public function onRequest(Swoole\Http\Request $request, Swoole\Http\Response $response)
    {
        \Log\JkdLog::channel('memory', '请求前', memory_get_usage());
//        \xhprof_enable(XHPROF_FLAGS_MEMORY + XHPROF_FLAGS_CPU+XHPROF_FLAGS_NO_BUILTINS);

        ini_set('memory_limit', '-1');
        ini_set('display_errors', 'On');    //是否显示错误
        ini_set('error_reporting', E_ALL);  //设置错误的报告级别

        $response->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT');
        $response->header('Access-Control-Allow-Origin', '*');  //解决跨域
        $response->header('Content-Type', 'application/json');

        //注册全局信息
        $this->initRequestParam($request);
        Yaf\Registry::set('SWOOLE_HTTP_REQUEST', $request);
        Yaf\Registry::set('SWOOLE_HTTP_RESPONSE', $response);

        $requestRoute = $request->server['request_uri'] ?? '';
        $requestMethod = $request->server['request_method'] ?? '';
        $requestRouteData = \Route\JkdRoute::get()->getRoute($requestRoute);
        $requestRoute = $requestRouteData['action'] ?? '';
        $needMethod = $requestRouteData['method'] ?? '';
        $needMethod = strtoupper($needMethod);
        if ($requestRoute && $needMethod == $requestMethod) {
            ob_start();
            $yafRequest = new Yaf\Request\Http($requestRoute);
            Yaf\Registry::set('YAF_HTTP_REQUEST', $yafRequest);

            //关闭视图
            Yaf\Dispatcher::getInstance()->autoRender(FALSE);
            $this->app->getDispatcher()->dispatch($yafRequest);
            $result = ob_get_contents();
            ob_end_clean();

            //返回数据处理
            $result = $result ? json_decode($result, true) : [];
        } else {
            $result = ['code' => 0, 'message' => '404 not found', 'data' => [], 'status' => 404];
        }

        $status = 200;
        if (isset($result['status']) && $result['status']) {
            $status = $result['status'];
            unset($result['status']);
        }
        $response->status($status);
        $response->end(json_encode($result));
        \Log\JkdLog::channel('memory', '请求后', memory_get_usage());
//        $xhprof_data = \xhprof_disable();

//        include_once  '/www/xhprof/xhprof_lib/utils/xhprof_lib.php';
//        include_once  '/www/xhprof/xhprof_lib/utils/xhprof_runs.php';
//        $xhprof_runs = new \XHProfRuns_Default();
//        $run_id = $xhprof_runs->save_run($xhprof_data, $this->appName);
    }


    /**
     * 将请求信息放入全局注册器中
     *
     * @param \Swoole\Http\Request $request
     * @return bool
     */
    private function initRequestParam(Swoole\Http\Request $request)
    {
        //将请求的一些环境参数放入全局变量桶中
        $server = $request->server ?? [];
        $header = $request->header ?? [];
        $get = $request->get ?? [];
        $post = $request->post ?? [];
        $cookie = $request->cookie ?? [];
        $files = $request->files ?? [];

        Yaf\Registry::set('REQUEST_SERVER', $server);
        Yaf\Registry::set('REQUEST_HEADER', $header);
        Yaf\Registry::set('REQUEST_GET', $get);
        Yaf\Registry::set('REQUEST_POST', $post);
        Yaf\Registry::set('REQUEST_COOKIE', $cookie);
        Yaf\Registry::set('REQUEST_FILES', $files);
        Yaf\Registry::set('REQUEST_RAW_CONTENT', $request->rawContent());

        $params = $get ?: $post;
        Yaf\Registry::set('REQUEST_PARAMS', $params);

        return true;
    }


    /**
     * 初始化连接数日志
     */
    private function createPoolLog()
    {
        //Redis连接数
        $path = APP_PATH . '/runtime/pool/redis_pool_num.count';
        file_put_contents($path, '{}');

        //Mysql连接数
        $path = APP_PATH . '/runtime/pool/mysql_pool_num.count';
        file_put_contents($path, '{}');
    }


    /**
     * 打开Redis连接池
     *
     * @param $workerId
     */
    private function startRedis($workerId)
    {
        // 启动数据库连接池
        \Pool\JkdRedisPool::run()->init();
        // 启动连接池检测定时器
        \Pool\JkdRedisPool::run()->timingRecovery($workerId);
    }


    /**
     * 打开MYsql连接池
     *
     * @param $workerId
     */
    private function startMysql($workerId)
    {
        // 启动数据库连接池
        \Pool\JkdMysqlPool::run()->init();
        // 启动连接池检测定时器
        \Pool\JkdMysqlPool::run()->timingRecovery($workerId);
    }


    /**
     * 开启定时任务
     */
    private function startCron($masterPid)
    {
        \Cron\JkdCron::start($masterPid, $this->jkdYafConfig['common']['timer_pid_file']);
    }

}