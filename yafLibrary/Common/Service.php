<?php

namespace Common;

use Yaf\Loader;

class Service
{


    /**
     * 加载services类
     *
     * @param string $name
     */
    public static function loaderService(string $name)
    {
        $path = APP_PATH . '/app/services/' . $name . '.php';

        Loader::import($path);
    }

}