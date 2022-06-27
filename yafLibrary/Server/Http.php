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

use Conf\JkdConf;
use Constant\HttpCode;
use Constant\HttpMessage;
use Jkd\JkdSubassembly;
use Job\JkdSysLog;
use Limit\RouteLimit;
use Route\JkdRoute;
use Swoole\Coroutine\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Task\JkdTask;

class Http
{
    protected $jkdYafConfig;

    protected $ip;

    protected $port;

    protected $daemonize;

    protected $app;

    protected $header;

    public function __construct($jkdYafConfig, $daemonize)
    {
        $this->jkdYafConfig = $jkdYafConfig;
        $this->ip = (string) $this->jkdYafConfig['ip'];
        $this->port = (int) $this->jkdYafConfig['port'];
        $this->daemonize = $daemonize;
    }

    public function start($masterPid, $workerId)
    {
        $server = new Server($this->ip, $this->port, false, true);

        // 实例化yaf
        $this->app = new \Yaf\Application(APP_PATH . '/conf/app.ini');
        $this->app->bootstrap();

        // 设置时区
        date_default_timezone_set('PRC');

        // 启动服务
        $subassembly = new JkdSubassembly();
        $subassembly->handle($masterPid, $workerId, $this->jkdYafConfig);

        // 获取header 配置
        $this->header = JkdConf::get('header', false);
        $this->header = $this->header ? $this->header->toarray() : [];

        // 捕获错误
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            \SeasLog::error('set_error_handler:' . json_encode([
                'Code:' => $errno,
                'Msg:' => $errstr,
                'File:' => $errfile,
                'Line:' => $errline,
            ]));
        }, E_ALL);

        $routePreList = JkdRoute::get()->getRoutePrefixs();
        foreach ($routePreList as $routePre) {
            $thisPreUri = $routePre ? '/' . $routePre : '';
            $server->handle($thisPreUri . '/', [$this, 'onRequest']);
        }
        if ($workerId == 0 && $this->daemonize != true) {
            $routeList = JkdRoute::get()->getRouteList();
            foreach ($routeList as $uri => $route) {
                echo $uri . '--' . json_encode($route) . PHP_EOL;
            }
        }
        $server->start();
    }

    public function onRequest(Request $request, Response $response)
    {
//        $_ENV['ATOMIC']['REQUEST']->add();

        include_once '/www/wwwroot/xhprof/xhprof-master/xhprof_lib/utils/xhprof_lib.php';
        include_once '/www/wwwroot/xhprof/xhprof-master/xhprof_lib/utils/xhprof_runs.php';

        \xhprof_enable(XHPROF_FLAGS_MEMORY + XHPROF_FLAGS_CPU + XHPROF_FLAGS_NO_BUILTINS);

//        echo '运行前内存：' . round(memory_get_usage() / 1024 getRouteType/ 1024, 2) . 'MB', PHP_EOL;
        $_startTime = microtime(true);
        ini_set('memory_limit', '-1');
        ini_set('display_errors', 'On');    // 是否显示错误
        ini_set('error_reporting', E_ALL);  // 设置错误的报告级别

        // 设置 header
        $this->setHeader($response);
        $clientIp = $request->header['x-real-ip'] ?? ($request->server['remote_addr'] ?: '');
        $reqMethod = $request->server['request_method'] ?? '';  // 请求方式
        $requestUri = $request->server['request_uri'] ?? '';
        $reqRouteData = JkdRoute::get()->getRoute($requestUri);
        $limit = $reqRouteData['limit'] ?? [];          // 限流
        $reqRoute = $reqRouteData['action'] ?? '';      // 请求路由
        $needMethod = $reqRouteData['method'] ?? [];    // 所需请求方式

        if ($reqRoute && in_array($reqMethod, $needMethod)) {
            // 注册全局信息
            if ($limit && ! RouteLimit::checkLimit($limit, $clientIp, $requestUri)) {
                $result = ['code' => 1, 'message' => HttpMessage::TOO_MANY_REQUEST, 'data' => [], 'status' => HttpCode::TOO_MANY_REQUEST];
            } else {
                ob_start();
                $yafRequest = new \Yaf\Request\Http($reqRoute);
                $this->initRequestParam($request, $reqMethod, $clientIp, $yafRequest);
                // 关闭视图
                \Yaf\Dispatcher::getInstance()->autoRender(false);

                $this->app->getDispatcher()->dispatch($yafRequest);
                $result = ob_get_contents();

                ob_end_clean();
                // 返回数据处理
                $result = $result ? json_decode($result, true) : [];
            }
        } else {
            $result = ['code' => 1, 'message' => HttpMessage::NOT_FUND, 'data' => [], 'status' => HttpCode::NOT_FUND];
        }

        $status = $result['status'] ?? HttpCode::SUCCESS;
        unset($result['status']);

        $_endTime = microtime(true);
        if ($this->daemonize != true) {
            \Jkd::reqMsg($_startTime, $_endTime, $status, $request->server, $clientIp);
        }

        // 储存请求日志
        if (checkIoStatus('reqLogStatus')) {
            JkdTask::dispatch(JkdSysLog::class, [
                'runtime' => $_endTime - $_startTime,
                'route' => $requestUri ?? $reqRoute,
                'params' => getJkdYafParams('JKDYAF_PARAMS'),
                'result' => $result,
            ]);
        }

        $response->status($status);
        $response->end(json_encode($result));
//        echo '运行后内存：' . round(memory_get_usage() / 1024 / 1024, 2) . 'MB', PHP_EOL, PHP_EOL;

        $xhprof_data = \xhprof_disable();
        $xhprof_runs = new \XHProfRuns_Default();
        $run_id = $xhprof_runs->save_run($xhprof_data, 'JKDYAF');
    }

    // 设置 header
    private function setHeader(Response $response)
    {
        foreach ($this->header as $key => $header) {
            $response->header($key, $header['VALUE'], $header['FORMAT']);
        }
    }

    /**
     * 将请求信息放入yaf请求中.
     *
     * @param $reqMethod
     * @param $clientIp
     * @param $yafRequest
     */
    private function initRequestParam(Request $request, $reqMethod, $clientIp, &$yafRequest)
    {
        $get = $request->get ?? [];
        $post = $request->post ?? [];
        $postContent = $request->getContent() ?? '';
        $postContent = json_decode($postContent, true);

        $params = $reqMethod == 'GET' ? $get : ($post ?: $postContent);
        if ($params && is_array($params)) {
            foreach ($params as $i => $requestParam) {
                $requestParam = remove_xss($requestParam);
                $params[$i] = safe_replace($requestParam);
            }
        }

        $yafRequest->setParam('JKDYAF_REQ', $request);
        $yafRequest->setParam('JKDYAF_PARAMS', $params);
        $yafRequest->setParam('JKDYAF_CLIENT_IP', $clientIp);
        $params = null;
    }
}
