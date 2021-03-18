<?php
/**
 * 日志类
 */

class JkdLog
{

    /**
     * 感兴趣的事件。像用户登录，SQL日志
     *
     * @param $message //信息
     * @param array $content //数据
     * @param string $channel //通道
     */
    public static function info($message, $content = [], $channel = '')
    {
        self::writeLog($message, $content, $channel);
    }


    /**
     * 运行时发生了错误，错误需要记录下来并监视，但错误不需要立即处理。
     *
     * @param $content //数据
     */
    public static function error($content)
    {
        $content = is_array($content) ? $content : (array)$content;
        self::writeLog('Runtime error', $content, 'error');
    }


    /**
     * 详细的debug信息
     *
     * @param $message //信息
     * @param array $content //数据
     */
    public static function debug($message, $content = [])
    {
        self::writeLog($message, $content, 'debug');
    }


    /**
     * 写日志
     *
     * @param string $message
     * @param array $content
     * @param string $channel
     */
    protected static function writeLog($message, $content, $channel = '')
    {
        if (!is_string($content)) {
            $content = json_encode($content);
        }
        
        $logPath = \Yaf\Registry::get('config')->logPath;   //日志路径
        $dir = $logPath . $channel;
        if (!is_dir($dir)) {
            @mkdir($dir, 0777);
        }
        $filename = ($channel ?: 'jkd') . '-' . date('Y-m-d') . '.log';

        $str = '[' . date('Y-m-d H:i:s') . ']' . ' ' . $message . ' ' . $content . PHP_EOL;

        file_put_contents($dir . '/' . $filename, $str, FILE_APPEND | LOCK_EX);
    }


}