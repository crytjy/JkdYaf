<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace UnAutoLoader;

use Conf\JkdConf;
use Plugin\JkdPlugin;
use Route\JkdRouteRegistrar;

class JkdBootstrap extends \Yaf\Bootstrap_Abstract
{
    /**
     * 加载文件.
     */
    public function _initLoader()
    {
        \Yaf\Loader::import(LIB_PATH . '/UnAutoLoader/JkdHelper.php');

        // 加载Jkd类
        $this->JkdLoader();
    }

    public function _initConfig()
    {
        // 把配置保存起来
        $arrConfig = \Yaf\Application::app()->getConfig();
        \Yaf\Registry::set('config', $arrConfig);

        // 保存redisKey
        $redisKeyConfig = JkdConf::get('redisKey');
        \Yaf\Registry::set('redisKeyConf', $redisKeyConfig);

        // 保存channel
        $channelConfig = JkdConf::get('channel');
        \Yaf\Registry::set('channelConfig', $channelConfig);
    }

    /**
     * 加载类.
     */
    public function _initClassLoader()
    {
        $classList = ['services', 'crontab', 'middleware', 'task'];
        foreach ($classList as $class) {
            $firstPath = APP_PATH . '/app/' . $class . '/';
            importFile($firstPath);
        }
    }

    public function _initPlugin(\Yaf\Dispatcher $dispatcher)
    {
        // 注册插件
        $jkdPlugin = new JkdPlugin();
        $dispatcher->registerPlugin($jkdPlugin);
    }

    public function _initRoute(\Yaf\Dispatcher $dispatcher)
    {
        // 在这里注册自己的路由协议,默认使用简单路由
    }

    /**
     * 加载公用类.
     */
    public function JkdLoader()
    {
        $firstPath = LIB_PATH . '/';
        $data = getDirContent($firstPath);
        foreach ($data as $da) {
            if (! is_file($firstPath . $da)) {
                if (in_array($da, ['UnAutoLoader', 'bin', 'ConfigFile', 'Commands', 'Server', 'Facades', 'vendor'])) {
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
        // 注册路由
        $routeRegistrar = new JkdRouteRegistrar();
        $routeRegistrar->handle();
    }

    /**
     * 注册composer.
     */
    public function _initAutoload()
    {
        // Autoload 自动载入
        $autoFile = APP_PATH . '/vendor/autoload.php';
        if (file_exists($autoFile)) {
            require $autoFile;
            $autoFile = null;
        }
    }
}
