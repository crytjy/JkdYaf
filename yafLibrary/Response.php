<?php

/**
 * 响应类
 *
 * Class Response
 */
class Response
{

    /**
     * 返回json
     *
     * @param $array
     * @throws Exception
     */
    public static function output($array)
    {
        echo json_encode($array);
        throw new Exception('JkdReturn', 676);
    }


    /**
     * 成功返回json
     *
     * @param string $data
     * @param string $message
     * @param int $status
     * @param int $code
     */
    public static function Success($data = "", $message = "成功", $status = 200, $code = 1)
    {
        $outArray = ['code' => $code, 'message' => $message, 'data' => $data, 'status' => $status];
        self::output($outArray);
    }


    /**
     * 失败返回json
     *
     * @param string $message
     * @param int $status
     * @param int $code
     */
    public static function Fail($message = "失败", $status = 200, $code = 0)
    {
        $outArray = ['code' => $code, 'message' => $message, 'status' => $status];
        self::output($outArray);
    }

}
