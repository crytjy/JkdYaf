<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Jkd;

class JkdBaseError extends \Yaf\Controller_Abstract
{
    // 从2.1开始, errorAction支持直接通过参数获取异常
    public function errorAction($exception)
    {
        if ($exception->getCode() != 676) {
            \SeasLog::error('errorAction:' . json_encode([
                'errorCode' => $exception->getCode(),
                'errorMessage' => $exception->getMessage(),
                'exception' => (array) $exception,
            ]));
            JkdResponse::Error();
        }
    }
}
