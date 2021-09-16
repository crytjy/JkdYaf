<?php

namespace Auth;

use Jwt\JWT;

class JwtAuth
{

    const Key = 'yEPw8Gd2K9gM9T3S0yqHkzKiQ3XkiOcrLC1Hx5Cw1sTT3xkJVaFq2wcdjLpbiWkG';


    /**
     * 生成token
     *
     * @param $guid //用户ID
     * @return mixed
     */
    public static function getToken($guid)
    {
        $siteUrl = $logPath = \Yaf\Registry::get('config')->siteUrl;   //域名
        $nowtime = time();
        $token = [
            "iss" => $siteUrl,       //签发人
            "aud" => $siteUrl,       //受众
            "iat" => $nowtime,       //签发时间
            "nbf" => $nowtime + 0,   //生效时间
            'exp' => $nowtime + 600, //过期时间-10min
            'guid' => $guid  //自定义参数，用户ID
        ];

        // encode
        $jwt = JWT::encode($token, self::Key, 'HS256'); //默认就是 'HS256'

        return $jwt;
    }


    /**
     * 解密token
     *
     * @param $jwt
     * @return array
     */
    public static function checkToken($jwt)
    {
        // decode
        $res = JWT::decode($jwt, self::Key, ['HS256']);
        $res = $res ? json_decode(json_encode($res), true) : [];
        return $res['guid'] ?? 0;
    }

}