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

class HttpCode
{
    public const JKD_RETURN = 676;

    public const SUCCESS = 200;                // 接口处理成功

    public const FAIL = 305;                   // 接口处理失败

    public const DEBUG = 400;                  // DEBUG

    public const NOT_FUND = 404;               // NOT_FUND

    public const SERVER_ERROR = 500;           // 系统错误

    public const VALIDATION_ERROR = 301;       // 请求数据验证失败

    public const TOO_MANY_REQUEST = 429;       // 请求过于频繁
}
