<?php

include __DIR__ . "/bin/Jkd.php";

class HttpServer
{

    private $jkdYafConfig;

    private $server;

    private $app;

    protected static $instance = null;
    protected static $daemonize = null;

    private $ip;

    private $port;


    private function __construct()
    {
        // 获取JKdYaf配置文件
        $this->jkdYafConfig = parse_ini_file(APP_PATH . "/conf/jkdYaf.ini", true);

        $this->ip = $this->jkdYafConfig['common']['ip'] ?? '';
        $this->port = $this->jkdYafConfig['common']['port'] ?? '';
    }


    /**
     * @param bool $daemonize //守护进程  true|false
     *
     * @return HttpServer|null
     */
    public static function getInstance($daemonize = false)
    {
        if (empty(self::$instance) || !(self::$instance instanceof HttpServer)) {
            self::$instance = new self();
            self::$daemonize = $daemonize;
        }

        return self::$instance;
    }


    /**
     * 启动
     */
    public function start()
    {
        $this->server = new Swoole\Http\Server($this->ip, $this->port);
        $this->jkdYafConfig['server']['daemonize'] = self::$daemonize;

        $this->server->set($this->jkdYafConfig['server']);
        $this->server->on('Start', [$this, 'onStart']);
        $this->server->on('ManagerStart', [$this, 'onManagerStart']);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('WorkerStop', [$this, 'onWorkerStop']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->start();
    }


    public function onStart(Swoole\Http\Server $server)
    {
        swoole_set_process_name($this->jkdYafConfig['common']['master_process_name']);
        \Jkd::start($server->manager_pid, $server->master_pid, $this->ip, $this->port, self::$daemonize);
        return true;
    }


    public function onManagerStart(Swoole\Http\Server $server)
    {
        swoole_set_process_name($this->jkdYafConfig['common']['manager_process_name']);

        return true;
    }


    public function onWorkerStart(Swoole\Http\Server $server, $workerId)
    {
        $processName = sprintf($this->jkdYafConfig['common']['event_worker_process_name'], $workerId);
        swoole_set_process_name($processName);

        //实例化yaf
        $this->app = new Yaf\Application(APP_PATH . "/conf/app.ini");
        $this->app->bootstrap();

        return true;
    }


    public function onWorkerStop(Swoole\Http\Server $server, $workerId)
    {
        return true;
    }


    public function onRequest(Swoole\Http\Request $request, Swoole\Http\Response $response)
    {
        $response->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT');
        $response->header('Access-Control-Allow-Origin', '*');  //解决跨域
        $response->header('Content-Type', 'application/json');

        //注册全局信息
        $this->initRequestParam($request);
        Yaf\Registry::set('SWOOLE_HTTP_REQUEST', $request);
        Yaf\Registry::set('SWOOLE_HTTP_RESPONSE', $response);

        //关闭视图
        Yaf\Dispatcher::getInstance()->autoRender(FALSE);

        //执行
        ob_start();
        try {
            $yafRequest = new Yaf\Request\Http($request->server['request_uri']);

            $configArr = Yaf\Application::app()->getConfig()->toArray();
            if (!empty($configArr['application']['baseUri'])) { //set base_uri
                $yafRequest->setBaseUri($configArr['application']['baseUri']);
            }

            $this->app->getDispatcher()->dispatch($yafRequest);
        } catch (\Exception $e) {
            \JkdLog::error($e);
        }

        $result = ob_get_contents();
        ob_end_clean();

        //返回数据处理
        $result = $result ? json_decode($result, true) : [];
        $status = 200;
        if (isset($result['status']) && $result['status']) {
            $status = $result['status'];
            unset($result['status']);
        }

        $response->status($status);
        $response->end(json_encode($result));
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

        if ($request->server['request_method'] == 'GET') {
            Yaf\Registry::set('REQUEST_PARAMS', $get);
        } elseif ($request->server['request_method'] == 'POST') {
            Yaf\Registry::set('REQUEST_PARAMS', $post);
        } else {
            Yaf\Registry::set('REQUEST_PARAMS', []);
        }

        return true;
    }
}