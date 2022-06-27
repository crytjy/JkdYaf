<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Commands;

require LIB_PATH . 'bin/Jkd.php';
class Command
{
    /**
     * php artisan make:services Api Test.
     * @param mixed $argv
     */
    public function handle($argv)
    {
        if (count($argv) < 3) {
            \Jkd::echoStr('missing command params.', 3);
            exit();
        }

        $filePath = '';
        $type = ($argv[1] ? explode(':', $argv[1]) : [])[1];  // 类型
        $filePath = LIB_PATH . 'Commands/MakeCommand/';

        if ($filePath) {
            require LIB_PATH . 'Commands/GeneratorCommand.php';
            $data = $this->getDirContent($filePath);
            foreach ($data as $da) {
                $thisPath = $filePath . $da;
                if (is_file($thisPath)) {
                    require $thisPath;
                    $className = '\Commands\MakeCommand\\' . explode('.php', $da)[0];
                    $serverObj = new $className($argv);
                    if ($serverObj->name == $argv[1]) {
                        $serverObj->handle();
                        exit();
                    }
                }
            }
        }

        \Jkd::echoStr('can not fount this command.', 3);
    }

    public function getDirContent($path)
    {
        if (! is_dir($path)) {
            return false;
        }
        // scandir方法
        $arr = [];
        $data = scandir($path);
        foreach ($data as $value) {
            if ($value != '.' && $value != '..') {
                $arr[] = $value;
            }
        }

        return $arr;
    }
}
