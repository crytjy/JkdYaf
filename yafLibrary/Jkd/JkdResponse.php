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

use Constant\HttpCode;
use Constant\HttpMessage;

/**
 * 响应类.
 *
 * Class JkdResponse
 */
class JkdResponse
{
    /**
     * 返回json.
     *
     * @param $array
     * @param mixed $isException
     * @throws Exception
     */
    public static function output($array, $isException = false)
    {
        echo json_encode($array);
        if ($isException) {
            throw new \Exception(HttpMessage::JKD_RETURN, HttpCode::JKD_RETURN);
        }
        return true;
    }

    /**
     * 成功返回json.
     *
     * @param string $data
     * @param string $message
     * @param int $status
     * @param int $code
     * @return bool
     */
    public static function Success($data = '', $message = HttpMessage::SUCCESS, $status = HttpCode::SUCCESS, $code = 0)
    {
        $outArray = ['code' => $code, 'message' => $message, 'data' => $data, 'status' => $status];
        return self::output($outArray);
    }

    /**
     * 失败返回json.
     *
     * @param string $message
     * @param int $status
     * @param int $code
     * @throws Exception
     */
    public static function Fail($message = HttpMessage::FAIL, $status = HttpCode::FAIL, $code = 1)
    {
        $outArray = ['code' => $code, 'message' => $message, 'status' => $status];
        return self::output($outArray, true);
    }

    /**
     * 错误返回json.
     *
     * @param string $message
     * @param int $status
     * @param int $code
     * @throws Exception
     */
    public static function Error($message = HttpMessage::SERVER_ERROR, $status = HttpCode::SERVER_ERROR, $code = 2)
    {
        $outArray = ['code' => $code, 'message' => $message, 'status' => $status];
        return self::output($outArray);
    }

    /**
     * debug返回json.
     *
     * @param string $data
     * @param string $message
     * @param int $status
     * @param int $code
     * @throws Exception
     */
    public static function Debug($data = '', $message = HttpMessage::DEBUG, $status = HttpCode::DEBUG, $code = 3)
    {
        $outArray = ['code' => $code, 'message' => $message, 'data' => $data, 'status' => $status];
        return self::output($outArray, true);
    }
}
