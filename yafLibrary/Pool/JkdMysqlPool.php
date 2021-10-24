<?php
/**
 * Mysql连接池
 *
 * @author JKD
 * @date 2021年10月20日 23:34
 */

namespace Pool;

use Conf\JkdConf;
use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;

class JkdMysqlPool
{
    /**
     * 是否开启连接数监控
     */
    protected $isMonitor;

    /**
     * 最大连接数
     */
    protected $max;

    /**
     * 当前连接数
     */
    protected $count;

    /**
     * 连接池组
     */
    protected $connections;

    /**
     * 配置项
     */
    protected $config;

    /**
     * 创建静态对象变量,用于存储唯一的对象实例
     */
    protected static $instance = null;

    /**
     * 私有化克隆函数，防止外部克隆对象
     */
    private function __clone()
    {
    }


    /**
     * 初始化参数
     *
     * RedisPool constructor.
     */
    private function __construct()
    {
        // 读取配置类
        $config = \Yaf\Registry::get('dbConf');
        $this->max = (int)$config['pool_max'];
        $this->isMonitor = $config['is_monitor'];
        $this->config = [
            'host' => $config['db']['host'],
            'port' => (int)$config['db']['port'],
            'dbname' => $config['db']['dbname'],
            'charset' => $config['db']['charset'],
            'username' => $config['db']['username'],
            'password' => $config['db']['password'],
        ];
    }


    public static function run()
    {
        // 只有第一次调用，才允许创建对象实例
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * 初始化连接池
     */
    public function init()
    {
        $this->createMysql();
        $this->count = $this->max;
    }


    /**
     * 创建数据库连接实例
     */
    protected function createMysql()
    {
        $this->connections = new PDOPool((new PDOConfig)
            ->withHost($this->config['host'])
            ->withPort($this->config['port'])
            // ->withUnixSocket('/tmp/mysql.sock')
            ->withDbName($this->config['dbname'])
            ->withCharset($this->config['charset'])
            ->withUsername($this->config['username'])
            ->withPassword($this->config['password'])
            , $this->max);
    }


    /**
     * 获取一个连接
     *
     * @return bool
     * @throws \Exception
     */
    public function pop()
    {
        if ($this->count <= 0) {
            $this->popError('write');
            throw new \Exception("Mysql Pop <= 0");
            return false;
        }
        $this->count--;
        if (!$this->connections) return false;
        return $this->connections->get();
    }


    /**
     * 归还一个连接
     *
     * @param $obj //数据库连接实例
     * @return bool
     */
    public function free($obj)
    {
        $this->count++;
        if (!$this->connections) return false;
        return $this->connections->put($obj);
    }


    /**
     * 定时回收空闲连接
     *
     * @param $workerId
     */
    public function timingRecovery($workerId)
    {
        // 5秒更新一次当前Redis连接数
        if ($this->isMonitor) {
            \Swoole\Timer::tick(5000, function () use ($workerId) {
                $path = APP_PATH . '/runtime/pool/mysql_pool_num.count';
                $json = \Swoole\Coroutine\System::readFile($path);
                $array = [];
                if ($json) {
                    $array = json_decode($json, true);
                }
                $array[$workerId] = $this->count;
                \Swoole\Coroutine\System::writeFile($path, json_encode($array));
                unset($json);
                unset($array);
                unset($path);
            });
        }
    }


    /**
     * 清空连接池
     */
    public function clean()
    {

    }


    /**
     * 当连接池数小于等于0时，回调的通知函数
     *
     * @param string $type 连接池类型
     * @return bool
     * @throws \Exception
     */
    protected function popError($type)
    {
        // 此处可自行实现消息通知
        throw new \Exception(" Mysql 连接数不足！");
        return false;
    }


    /**
     * 获取最大连接数
     *
     * @return mixed
     */
    public function max()
    {
        return $this->max;
    }


    /**
     * 获取当前连接数
     *
     * @return mixed
     */
    public function used()
    {
        return $this->count ?? 0;
    }

}