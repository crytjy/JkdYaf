<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Middleware;

use Auth\JwtAuth;
use Jkd\JkdResponse;

class JkdAuth
{
    public function handle()
    {
        // 请求参数
        $params = getJkdYafParams('JKDYAF_PARAMS');
        $token = $params['token'] ?? '';
        if (! $token) {
            JkdResponse::Fail('Missing Token');
        }
        $userKey = JwtAuth::checkToken($token);
        if (! $userKey) {
            JkdResponse::Fail('Invalid Token');
        }

        return true;
    }
}
