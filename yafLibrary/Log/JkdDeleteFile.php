<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Log;

use Conf\JkdConf;

class JkdDeleteFile
{
    // 日志级别
    public const LOG_LEVEL = [
        SEASLOG_DEBUG,
        SEASLOG_INFO,
        SEASLOG_NOTICE,
        SEASLOG_WARNING,
        SEASLOG_ERROR,
        SEASLOG_CRITICAL,
        SEASLOG_ALERT,
        SEASLOG_EMERGENCY,
    ];

    /**
     * 删除日志文件.
     *
     * @param string $name
     */
    public static function delLogs()
    {
        $logConf = JkdConf::get('log');
        $day = $logConf->day ?? 7;
        $basePath = $logConf->basePath ?? APP_PATH . '/runtime/log';   // 日志路径
        $defaultFilePrefix = $logConf->default_file_prefix ?? '';
        $defaultFileDatetimeSeparator = $logConf->default_file_datetime_separator ?? '';
        $datetime = date('Y' . $defaultFileDatetimeSeparator . 'm' . $defaultFileDatetimeSeparator . 'd', strtotime('-' . $day . 'days'));

        $data = getDirContent($basePath);
        foreach ($data as $da) {
            foreach (self::LOG_LEVEL as $level) {
                $logPath = $basePath . '/' . $da . '/' . $defaultFilePrefix . $datetime . '.' . $level . '.log';
                if (file_exists($logPath)) {
                    unlink($logPath);
                }
            }
        }
    }
}
