<?php
/**
 * 路由验证类
 *
 * Class Routes
 */

class JkdRoutes
{

    public static $guid;

    public static function checkRoute()
    {
        $serverData = Yaf\Registry::get('REQUEST_SERVER');
        $method = $serverData['request_method'] ?? ''; //客户端请求的方式
        $route = $serverData['request_uri'] ?? '';  //客户端请求的路由

        $data = \Yaf\Registry::get('routeConf')->$route;
        $res = $data ? explode(',', $data) : [];
        $needMethod = $res[0] ?? '';
        $isAuth = $res[1] ?? '';

        // 验证请求方式
        if ($needMethod != $method) {
            \Response::Fail('Looks like something went wrong.', 403, -2);
        }

        //请求参数
        $params = Yaf\Registry::get('REQUEST_PARAMS');

        //验证签名
//        self::checkSign($params);

        // 验证是否需要登录
        if ($isAuth == 'TRUE' || $isAuth == 'true') {
            $token = $params['token'] ?? '';
            $guid = \Auth\JwtAuth::checkToken($token);
            if (!$guid) {
                \Response::Fail('token异常', 401);
            }
            $info = \User\UserYac::get($guid);
            if (!$info) {
                \Response::Fail('找不到该用户', 401);
            }

            if (isset($info['status']) && $info['status'] != 1) {
                \Response::Fail('账号异常', 401);
            }

//            \JkdLog::debug('guid', $guid);

            self::$guid = $guid;
        }
    }


    /**
     * 验证签名
     */
    private static function checkSign($params)
    {
        //验证请求时的时间戳，不能超过1分钟
        if (!isset($params['ts']) || $params['ts'] > time() || (time() - $params['ts'] > 6000)) {
            \Response::Fail('Exception request', 403, -4);
        }

        if (!isset($params['sign'])) {
            \Response::Fail('Signature missing', 403, -5);
        }

        $apiAuth = new \Auth\ApiAuth();
        if ($apiAuth->getSign($params) != $params['sign']) {
            \Response::Fail('Signature Error', 403, -6);
        }
    }


}
