<?php
namespace Jkd;

/**
 * 路由验证类
 *
 * Class Routes
 */

class JkdRoutes
{
    public static function checkRoute()
    {
        $serverData = \Yaf\Registry::get('REQUEST_SERVER');
        $method = $serverData['request_method'] ?? ''; //客户端请求的方式
        $route = $serverData['request_uri'] ?? '';  //客户端请求的路由

        $thisRouteInfo = \Yaf\Registry::get('routeConf')->$route ?? '';
        //1. 对应方法路径 2.请求方式 GET POST 3.是否身份验证 4.是否签名验证
        list(, $needMethod, $isAuth, $isSign) = explode(',', $thisRouteInfo);
        $needMethod = strtoupper($needMethod);
        $isAuth = strtoupper($isAuth);
        $isSign = strtoupper($isSign);

        // 验证请求方式
        if ($needMethod != $method) {
            JkdResponse::Fail('Looks like something went wrong.');
        }

        //请求参数
        $params = \Yaf\Registry::get('REQUEST_PARAMS');

        //验证签名
        if ($isSign == 'TRUE') {
            self::checkSign($params);
        }

        // 验证是否需要登录
        if ($isAuth == 'TRUE') {
            $token = $params['token'] ?? '';
            if (!$token) {
                JkdResponse::Fail('Missing Token');
            }
            $userKey = \Auth\JwtAuth::checkToken($token);
            if (!$userKey) {
                JkdResponse::Fail('Invalid Token');
            }

//            $info = \User\UserRedis::get($userKey);
//            if (!$info) {
//                JkdResponse::Fail('找不到该用户');
//            }
//
//            if (isset($info['status']) && $info['status'] != 1) {
//                JkdResponse::Fail('账号异常');
//            }
        }

        return true;
    }


    /**
     * 验证签名
     */
    private static function checkSign($params)
    {
        //验证请求时的时间戳，不能超过1分钟
        if (!isset($params['ts']) || $params['ts'] > time() || (time() - $params['ts'] > 6000)) {
            JkdResponse::Fail('Exception request');
        }

        if (!isset($params['sign'])) {
            JkdResponse::Fail('Signature missing');
        }

        $apiAuth = new \Auth\ApiAuth();
        if ($apiAuth->getSign($params) != $params['sign']) {
            JkdResponse::Fail('Signature Error');
        }
    }


}
