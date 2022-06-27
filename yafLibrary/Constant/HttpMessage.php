<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Constant;

class HttpMessage
{
    public const JKD_RETURN = 'JkdReturn';

    public const SUCCESS = 'success';                                      // 接口处理成功

    public const FAIL = 'fail';                                            // 接口处理失败

    public const DEBUG = 'debug';                                          // DEBUG

    public const NOT_FUND = '404 not found';                               // NOT_FUND

    public const SERVER_ERROR = '500 System error!';                       // 系统错误

    public const VALIDATION_ERROR = 'validation error';                    // 请求数据验证失败

    public const TOO_MANY_REQUEST = '429 Too Many Requests';               // 请求过于频繁
}
