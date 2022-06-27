<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Cron;

use Conf\JkdConf;

class JkdCron
{
    public static function start($masterPid, $timerPidFile)
    {
        if (\Jkd\JkdPreventDuplication::check('CRON') || file_get_contents($timerPidFile) == $masterPid) {
            $config = JkdConf::get('crontab', false);
            $confArray = $config ? $config->toArray() : [];

            if (isset($confArray['is_start']) && $confArray['is_start'] == 1) {
                unset($confArray['is_start']);
                file_put_contents($timerPidFile, $masterPid);

                // 定时清除日志
                $confArray[] = [
                    'class' => '\Log\JkdDeleteFile',
                    'func' => 'delLogs',
                    'cronTime' => '0 0 * * *',
                ];

                // 定期清理碎片内存
                \Swoole\Timer::tick(3600000, function () {
//                    $memory = memory_get_usage();
                    gc_mem_caches();
                });

                $data = array_group($confArray ?? [], 'cronTime');
                self::startTimer($data);
            }
        }
    }

    private static function startTimer($data)
    {
        $parser = new JkdParser();
        $timerTimes = [];
        $timerTimeTasks = [];
        foreach ($data as $cronTime => $da) {
            if ($res = $parser->parse($cronTime)) {
                $timerTimes[$cronTime] = $res;
                $timerTimeTasks[$cronTime] = $da;
            }
        }

        if ($timerTimes && $timerTimeTasks) {
            \Swoole\Timer::tick(1000, function () use ($timerTimes, $timerTimeTasks, $parser) {
                $thisTime = time();
                foreach ($timerTimes as $cronTime => $timerTime) {
                    if (isset($timerTimeTasks[$cronTime]) && $parser->check($thisTime, $timerTime)) {
                        foreach ($timerTimeTasks[$cronTime] as $timerTimeTask) {
                            \Swoole\Timer::after(1, function () use ($timerTimeTask) {
                                $class = new $timerTimeTask['class']();
                                $func = $timerTimeTask['func'];
                                $class->{$func}();
                            });
                        }
                    }
                }
            });
        }
    }
}
