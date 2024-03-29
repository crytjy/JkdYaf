<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Conf;

class JkdConf
{
    /**
     * 获取配置.
     *
     * @param string $name 配置文件名称
     * @param false $isEnv 是否区分环境
     * @return false|\Yaf\Config\Ini
     */
    public static function get($name, $isEnv = true)
    {
        if (! $name) {
            return false;
        }

        $list = explode('.', $name);
        $fileName = $list[0];
        $key = $list[1] ?? '';

        $config = new \Yaf\Config\Ini(CONF_PATH . '/' . $fileName . '.ini');
        if ($isEnv != true) {
            if ($key) {
                return $config->get($key);
            }
            return $config;
        }
        if ($key) {
            return $config[environ()]->get($key);
        }
        return $config[environ()];
    }
}
