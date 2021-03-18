<?php

namespace app\services\Api;

class Index
{

    protected $modelUser;
    protected $JkdRequest;

    public function __construct($request = '')
    {
        $this->JkdRequest = $request;
        $this->modelUser = new \UserModel();
    }


    public function getAllUser()
    {
        $res = $this->modelUser->getAllUser();

        \Response::Success($res ?? [], "请求成功");
    }

    public function getToken()
    {
        $type = $this->JkdRequest['type'] ?? 1;
        if ($type == 1) {
            $guid = $this->JkdRequest['guid'] ?? 0;
            $res = \User\UserYac::get($guid);
            if ($res) {
                $token = \Auth\JwtAuth::getToken($guid);
//                $info = \Auth\JwtAuth::getUserInfo($token);

                \Response::Success(['token' => $token, 'userData' => $res], "请求成功");
            }

            \Response::Success('', "请求失败");
        } else {
            $token = $this->JkdRequest['token'] ?? '';
            $guid = \Auth\JwtAuth::checkToken($token);
            if ($guid) {
                $info = \User\UserYac::get($guid);


                \Response::Success(['userData' => $info, 'userId' => UserId()], "请求成功");
            }
            \Response::Success('', "请求失败");
        }
    }


    public function getCacheUser()
    {
        $redis = \Yaf\Registry::get('redis');
        $res = $redis->get('allUser');
        $res = $res ? json_decode($res, true) : [];

        \Response::Success($res ?? [], "请求成功");
    }


}
