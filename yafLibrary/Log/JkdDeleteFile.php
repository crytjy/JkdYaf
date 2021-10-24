<?php

namespace Log;

class JkdDeleteFile
{

    /**
     * 删除日志文件
     *
     * @param string $name
     */
    public static function delLogs()
    {
        $logConf = \Yaf\Registry::get('config')->log ?? '';
        $day = $logConf['day'] ?? 7;
        $path = $logConf['path'] ?? APP_PATH . '/runtime/log/';   //日志路径
        $datetime = date('Y-m-d', strtotime('-' . $day . 'days'));

        $data = getDirContent($path);
        foreach ($data as $da) {
            $logPath = $path . $da . '/' . $da . '-' . $datetime . '.log';
            if (file_exists($logPath)) {
                unlink($logPath);
            }
        }
    }

}