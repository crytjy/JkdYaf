<?php
/**
 * 配置类
 */

class JkdConf
{

    public static function get($name)
    {
        if (!$name)
            return false;

        $list = explode('.', $name);
        $fileName = $list[0];
        $key = $list[1] ?? '';

//        $confPath = Yaf\Application::app()->getConfig()->confPath;   //配置路径
        $config = new Yaf\Config\Ini(CONF_PATH . '/' . $fileName . '.ini');
        if ($key) {
            return $config[environ()]->get($key);
        } else {
            return $config[environ()];
        }
    }


}
