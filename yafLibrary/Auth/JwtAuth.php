<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Auth;

use Jwt\JWT;

class JwtAuth
{
    public const Key = 'yEPw8Gd2K9gM9T3S0yqHkzKiQ3XkiOcrLC1Hx5Cw1sTT3xkJVaFq2wcdjLpbiWkG';

    /**
     * 生成token.
     *
     * @param $guid //用户ID
     * @return mixed
     */
    public static function getToken($guid)
    {
        $siteUrl = \Yaf\Registry::get('channelConfig')->siteUrl;   // 域名
        $nowtime = time();
        $token = [
            'iss' => $siteUrl,          // 签发人
            'aud' => $siteUrl,          // 受众
            'iat' => $nowtime,          // 签发时间
            'nbf' => $nowtime,          // 生效时间
            'exp' => $nowtime + 86400,  // 过期时间-10min
            'guid' => $guid,            // 自定义参数，用户ID
        ];

        // encode
        return JWT::encode($token, self::Key, 'HS256'); // 默认就是 'HS256'
    }

    /**
     * 解密token.
     *
     * @param $jwt
     * @return mixed|string
     */
    public static function checkToken($jwt)
    {
        // decode
        $res = JWT::decode($jwt, self::Key, ['HS256']);
        $res = $res ? json_decode(json_encode($res), true) : [];
        return $res['guid'] ?? '';
    }
}
