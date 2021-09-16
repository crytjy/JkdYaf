<?php
/**
 * 路由验证类
 *
 * Class Routes
 */

class JkdRoutes
{
    public static function checkRoute()
    {
        $serverData = Yaf\Registry::get('REQUEST_SERVER');
        $method = $serverData['request_method'] ?? ''; //客户端请求的方式
        $route = $serverData['request_uri'] ?? '';  //客户端请求的路由

        $data = \Yaf\Registry::get('routeConf')->$route;
        $needMethod = $data['method'] ?? '';
        $isAuth = $data['auth'] ?? '';
        $isSign = $data['sign'] ?? 'false';

        // 验证请求方式
        if ($needMethod != $method) {
            \JkdResponse::Fail('Looks like something went wrong.');
        }

        //请求参数
        $params = Yaf\Registry::get('REQUEST_PARAMS');

        //验证签名
        if ($isSign == 'TRUE' || $isSign == 'true') {
            self::checkSign($params);
        }

        // 验证是否需要登录
        if ($isAuth == 'TRUE' || $isAuth == 'true') {
            $token = $params['token'] ?? '';
            $userKey = \Auth\JwtAuth::checkToken($token);

            if (!$userKey) {
                \JkdResponse::Fail('token异常');
            }
            $info = \User\UserRedis::get($userKey);
            if (!$info) {
                \JkdResponse::Fail('找不到该用户');
            }

            if (isset($info['status']) && $info['status'] != 1) {
                \JkdResponse::Fail('账号异常');
            }
        }
    }


    /**
     * 验证签名
     */
    private static function checkSign($params)
    {
        //验证请求时的时间戳，不能超过1分钟
        if (!isset($params['ts']) || $params['ts'] > time() || (time() - $params['ts'] > 6000)) {
            \Response::Fail('Exception request');
        }

        if (!isset($params['sign'])) {
            \JkdResponse::Fail('Signature missing');
        }

        $apiAuth = new \Auth\ApiAuth();
        if ($apiAuth->getSign($params) != $params['sign']) {
            \JkdResponse::Fail('Signature Error');
        }
    }


}
