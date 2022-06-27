<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
define('SEASLOG_ALL', 'ALL');
define('SEASLOG_DEBUG', 'DEBUG');
define('SEASLOG_INFO', 'INFO');
define('SEASLOG_NOTICE', 'NOTICE');
define('SEASLOG_WARNING', 'WARNING');
define('SEASLOG_ERROR', 'ERROR');
define('SEASLOG_CRITICAL', 'CRITICAL');
define('SEASLOG_ALERT', 'ALERT');
define('SEASLOG_EMERGENCY', 'EMERGENCY');
define('SEASLOG_DETAIL_ORDER_ASC', 1);
define('SEASLOG_DETAIL_ORDER_DESC', 2);
define('SEASLOG_CLOSE_LOGGER_STREAM_MOD_ALL', 1);
define('SEASLOG_CLOSE_LOGGER_STREAM_MOD_ASSIGN', 2);
define('SEASLOG_REQUEST_VARIABLE_DOMAIN_PORT', 1);
define('SEASLOG_REQUEST_VARIABLE_REQUEST_URI', 2);
define('SEASLOG_REQUEST_VARIABLE_REQUEST_METHOD', 3);
define('SEASLOG_REQUEST_VARIABLE_CLIENT_IP', 4);

class SeasLog
{
    public function __construct()
    {
        # SeasLog init
    }

    public function __destruct()
    {
        # SeasLog destroy
    }

    /**
     * 设置basePath.
     *
     * @param $basePath
     *
     * @return bool
     */
    public static function setBasePath($basePath)
    {
        return true;
    }

    /**
     * 获取basePath.
     *
     * @return string
     */
    public static function getBasePath()
    {
        return 'the base_path';
    }

    /**
     * 设置本次请求标识.
     *
     * @param string
     * @param mixed $request_id
     *
     * @return bool
     */
    public static function setRequestID($request_id)
    {
        return true;
    }

    /**
     * 获取本次请求标识.
     * @return string
     */
    public static function getRequestID()
    {
        return uniqid();
    }

    /**
     * 设置模块目录.
     *
     * @param $logger
     *
     * @return bool
     */
    public static function setLogger($logger)
    {
        return true;
    }

    /**
     * 手动清除logger的stream流
     *
     * @param int $model
     * @param string $logger
     *
     * @return bool
     */
    public static function closeLoggerStream($model = SEASLOG_CLOSE_LOGGER_STREAM_MOD_ALL, $logger)
    {
        return true;
    }

    /**
     * 获取最后一次设置的模块目录.
     * @return string
     */
    public static function getLastLogger()
    {
        return 'the lastLogger';
    }

    /**
     * 设置DatetimeFormat配置.
     *
     * @param $format
     *
     * @return bool
     */
    public static function setDatetimeFormat($format)
    {
        return true;
    }

    /**
     * 返回当前DatetimeFormat配置格式.
     * @return string
     */
    public static function getDatetimeFormat()
    {
        return 'the datetimeFormat';
    }

    /**
     * 设置请求变量.
     *
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public static function setRequestVariable($key, $value)
    {
        return true;
    }

    /**
     * 获取请求变量.
     *
     * @param $key
     *
     * @return string
     */
    public static function getRequestVariable($key)
    {
        return '';
    }

    /**
     * 统计所有类型（或单个类型）行数.
     *
     * @param string $level
     * @param string $log_path
     * @param null $key_word
     *
     * @return array|long
     */
    public static function analyzerCount($level = 'all', $log_path = '*', $key_word = null)
    {
        return [];
    }

    /**
     * 以数组形式，快速取出某类型log的各行详情.
     *
     * @param $level
     * @param string $log_path
     * @param null $key_word
     * @param int $start
     * @param int $limit
     * @param $order
     *
     * @return array
     */
    public static function analyzerDetail($level = SEASLOG_INFO, $log_path = '*', $key_word = null, $start = 1, $limit = 20, $order = SEASLOG_DETAIL_ORDER_ASC)
    {
        return [];
    }

    /**
     * 获得当前日志buffer中的内容.
     *
     * @return array
     */
    public static function getBuffer()
    {
        return [];
    }

    /**
     * 获取是否开启buffer.
     *
     * @return bool
     */
    public static function getBufferEnabled()
    {
        return true;
    }

    /**
     * 获取当前buffer count.
     *
     * @return int
     */
    public static function getBufferCount()
    {
        return 0;
    }

    /**
     * 将buffer中的日志立刻刷到硬盘.
     *
     * @return bool
     */
    public static function flushBuffer()
    {
        return true;
    }

    /**
     * 记录debug日志.
     *
     * @param array|string $message
     * @param string $module
     *
     * @return bool
     */
    public static function debug($message, array $context = [], $module = '')
    {
        # $level = SEASLOG_DEBUG
//        return true;
    }

    /**
     * 记录info日志.
     *
     * @param array|string $message
     * @param string $module
     *
     * @return bool
     */
    public static function info($message, array $context = [], $module = '')
    {
        # $level = SEASLOG_INFO
//        return true;
    }

    /**
     * 记录notice日志.
     *
     * @param array|string $message
     * @param string $module
     *
     * @return bool
     */
    public static function notice($message, array $context = [], $module = '')
    {
        # $level = SEASLOG_NOTICE
//        return true;
    }

    /**
     * 记录warning日志.
     *
     * @param array|string $message
     * @param string $module
     *
     * @return bool
     */
    public static function warning($message, array $context = [], $module = '')
    {
        # $level = SEASLOG_WARNING
//        return true;
    }

    /**
     * 记录error日志.
     *
     * @param array|string $message
     * @param string $module
     *
     * @return bool
     */
    public static function error($message, array $context = [], $module = '')
    {
        # $level = SEASLOG_ERROR
//        return true;
    }

    /**
     * 记录critical日志.
     *
     * @param array|string $message
     * @param string $module
     *
     * @return bool
     */
    public static function critical($message, array $context = [], $module = '')
    {
        # $level = SEASLOG_CRITICAL
//        return true;
    }

    /**
     * 记录alert日志.
     *
     * @param array|string $message
     * @param string $module
     *
     * @return bool
     */
    public static function alert($message, array $context = [], $module = '')
    {
        # $level = SEASLOG_ALERT
//        return true;
    }

    /**
     * 记录emergency日志.
     *
     * @param array|string $message
     * @param string $module
     *
     * @return bool
     */
    public static function emergency($message, array $context = [], $module = '')
    {
        # $level = SEASLOG_EMERGENCY
//        return true;
    }

    /**
     * 通用日志方法.
     *
     * @param $level
     * @param array|string $message
     * @param string $module
     *
     * @return bool
     */
    public static function log($level, $message, array $context = [], $module = '')
    {
//        return true;
    }
}
