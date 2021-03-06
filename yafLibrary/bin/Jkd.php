<?php

include __DIR__ . "/ConsoleTable.php";

class Jkd
{

    const GREEN_FONT = '0;32';
    const YELLOW_FONT = '0;33';

    /**
     * 设置颜色
     *
     * @param $str
     * @param $color
     * @return string
     */
    private static function setFont($str, $color)
    {
        return "\033[" . $color . "m" . $str . "\033[0m";
    }

    public static function start($managerPid, $masterPid, $ip, $port, $daemonize)
    {
        self::getTitle();
        $str = 'JkdYaf Start Success' . PHP_EOL;
        $str = self::setFont($str, self::GREEN_FONT);
        echo $str;
        self::getComponents($managerPid, $masterPid, $ip, $port);
        echo self::setFont('>>> Feedback:', self::YELLOW_FONT) . "\033[4m" . ' https://github.com/crytjy/JkdYaf.git' . "\033[0m" . PHP_EOL;
        if ($daemonize) {
            echo '[' . date('Y-m-d H:i:s') . '] [TRACE] Swoole is running in daemon mode, see "ps -ef|grep JkdYaf".' . PHP_EOL;
        } else {
            echo 'Swoole is running, press Ctrl+C to quit.' . PHP_EOL;
        }
    }


    private static function getTitle()
    {
        $str = '
       ____ __ ______  _____    ______
      / / //_// __ \ \/ /   |  / ____/
 __  / / ,<  / / / /\  / /| | / /_
/ /_/ / /| |/ /_/ / / / ___ |/ __/
\____/_/ |_/_____/ /_/_/  |_/_/
        ' . PHP_EOL;

        echo self::setFont($str, self::GREEN_FONT);;
    }


    private static function getComponents($managerPid, $masterPid, $ip, $port)
    {
        $phpVer = PHP_VERSION;  //php版本
        $swooleVer = SWOOLE_VERSION;  //swoole版本
        $yafVer = Yaf\VERSION;        //yaf版本
        $yacVer = phpversion('yac');        //yac版本

        $str1 = self::setFont('>>> Components', self::YELLOW_FONT);
        echo $str1 . PHP_EOL;
        $list = [
            ['PHP', $phpVer, '7.0 +'],
            ['Swoole', $swooleVer, '4.5 +'],
            ['YAC', $yacVer, '2.3 +'],
            ['YAF', $yafVer, '3.3 +'],
        ];

        $table = new \ConsoleTable(CONSOLE_TABLE_ALIGN_LEFT, CONSOLE_TABLE_BORDER_ASCII);
        echo $table->fromArray(
            ['Component', 'Version', 'Requirement'],
            $list
        );
        echo PHP_EOL;

        $table = new \ConsoleTable(CONSOLE_TABLE_ALIGN_LEFT, CONSOLE_TABLE_BORDER_ASCII);
        echo $table->fromArray(
            ['Protocol', 'Manager Pid', 'Master Pid', 'Listen At'],
            [['Main HTTP', $managerPid, $masterPid, $ip . ':' . $port]]
        );
        echo PHP_EOL;
    }

}
