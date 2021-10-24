<?php
/**
 * @name Bootstrap
 * @author root
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */

namespace UnAutoLoader;

use Conf\JkdConf;
use Plugin\JkdPlugin;
use Route\JkdRouter;

class JkdBootstrap extends \Yaf\Bootstrap_Abstract
{

    /**
     * 加载文件
     */
    public function _initLoader()
    {
        \Yaf\Loader::import(LIB_PATH . '/UnAutoLoader/JkdHelper.php');

        //加载Jkd类
        $this->JkdLoader();
    }

    public function _initConfig()
    {
        //把配置保存起来
        $arrConfig = \Yaf\Application::app()->getConfig();
        \Yaf\Registry::set('config', $arrConfig);

        //保存redisKey
        $redisKeyConfig = JkdConf::get('redisKey');
        \Yaf\Registry::set('redisKeyConf', $redisKeyConfig);

        //保存redis配置
        $redisConfig = JkdConf::get('redis');
        \Yaf\Registry::set('redisConf', $redisConfig);

        //保存db配置
        $dbConfig = JkdConf::get('db');
        \Yaf\Registry::set('dbConf', $dbConfig);
    }


    /**
     * 加载类
     */
    public function _initClassLoader()
    {
        $classList = ['services', 'crontab', 'middleware'];
        foreach ($classList as $class) {
            $firstPath = APP_PATH . '/app/' . $class . '/';
            importFile($firstPath);
        }
    }


    public function _initPlugin(\Yaf\Dispatcher $dispatcher)
    {
        //注册插件
        $jkdPlugin = new JkdPlugin();
        $dispatcher->registerPlugin($jkdPlugin);
    }


    public function _initRoute(\Yaf\Dispatcher $dispatcher)
    {
        //在这里注册自己的路由协议,默认使用简单路由
    }


    /**
     * 加载公用类
     */
    public function JkdLoader()
    {
        $firstPath = LIB_PATH . '/';
        $data = getDirContent($firstPath);
        foreach ($data as $da) {
            if (!is_file($firstPath . $da)) {
                if (in_array($da, ['UnAutoLoader', 'bin'])) {
                    continue;
                }
                $thisPath = $firstPath . $da;
                $files = getDirContent($thisPath);
                foreach ($files as $file) {
                    if (in_array($file, ['MysqlHandle.php'])) {
                        continue;
                    }
                    \Yaf\Loader::import($thisPath . '/' . $file);
                }
            }
        }
        $data = null;
    }


    public function _initJkdRouter()
    {
        $router = new JkdRouter();
        $router->handle();
    }

}