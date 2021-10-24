<?php
/**
 * 定时任务
 */
namespace Cron;

use Conf\JkdConf;
use Log\JkdDeleteFile;
use Log\JkdLog;

class JkdCron
{

    public static function start($masterPid, $timerPidFile)
    {
        if (\Jkd\JkdPreventDuplication::check('CRON') || file_get_contents($timerPidFile) == $masterPid) {
            $config = JkdConf::get('crontab', false);
            $confArray = $config ? $config->toArray() : [];

            if (isset($confArray['is_start']) && $confArray['is_start'] == true) {
                unset($confArray['is_start']);

                //定时删除日志
                \Swoole\Timer::tick(86460000, function () {
                    JkdDeleteFile::delLogs();
                });

                $data = array_group($confArray ?? [], 'msec');
                foreach ($data as $msec => $da) {
                    \Swoole\Timer::tick($msec, function () use ($da) {
                        foreach ($da as $d) {
                            $thisFunc = $d['func'] ?? '';
                            if ($thisFunc) {
                                $class = new $d['class']();
                                $class->$thisFunc();
                            }
                        }
                    });
                }
                file_put_contents($timerPidFile, $masterPid);
                JkdLog::channel('crontab', 'masterPid-' . $masterPid, \Swoole\Timer::list());
            }
        }
    }


}
