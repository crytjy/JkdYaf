<?php

if (!function_exists('jump')) {
    function dump($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE)
    {
        $label = (null === $label) ? '' : rtrim($label) . ':';
        ob_start();
        print_r($var);
        $output = ob_get_clean();
        $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
        if (Yaf\Dispatcher::getInstance()->getRequest()->isCli()) {
            $output = PHP_EOL . $label . $output . PHP_EOL;
        } else {
            if (!extension_loaded('xdebug')) {
                $output = htmlspecialchars($output, $flags);
            }

            $output = '<pre>' . $label . $output . '</pre>';
        }
        $output = '<pre>' . $label . $output . '</pre>';
        if ($echo) {
            echo($output);
            return;
        } else {
            return $output;
        }
    }
}


if (!function_exists('dd')) {
    function dd(...$vars)
    {
        echo '<pre>';
        foreach ($vars as $v) {
            print_r($v); echo ' ';
        }
        echo '</pre>';
        exit(1);
    }
}


/**
 * 获取环境
 */
if (!function_exists('environ')) {
    function environ()
    {
        return Yaf\Application::app()->environ();
    }
}


/**
 * 检查是否线上环境
 */
if (!function_exists('checkEnv')) {
    function checkEnv()
    {
        return Yaf\ENVIRON != 'product' ? true : false;
    }
}


/**
 * 获取用户ID
 */
if (!function_exists('UserId')) {
    function UserId()
    {
        return \JkdRoutes::$guid;
    }
}
