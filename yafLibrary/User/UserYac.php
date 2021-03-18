<?php

/**
 * 用户信息
 *
 * 将用户信息存储在YAC缓存中
 * 新增与更新都在YAC操作
 *
 *  再定时入库
 */

namespace User;

class UserYac
{

    /**
     * 获取YAC前缀
     *
     * @param bool $isNow
     * @return false|string
     */
    private static function getPrefix($isNow = true)
    {
        $prefix = 'UserData-';
        if ($isNow) {
            $prefix .= date('YmdH');
        } else {
            $prefix .= date('YmdH', strtotime('-1hours'));
        }

        return $prefix . ':';
    }


    /**
     * 获取用户信息
     *
     * @param $guid
     * @return mixed
     */
    public static function get($guid)
    {
        $yac = new \Yac\YacCache(self::getPrefix());
        $res = $yac->get($guid);

        if (!$res) {
            if (date('i') <= 5) {    //处理因0-5分之间入库处理
                $yac = new \Yac\YacCache(self::getPrefix(false));
                $res = $yac->get($guid);
            }

            if (!$res) {    //上次记录没有，则查询数据库
                $modelUser = new \UserModel();
                $res = $modelUser->getUser($guid);
            }

            //存入内存中
            self::add($guid, $res);
        }
        return $res;
    }


    /**
     * 新增用户信息
     *
     * @param $guid
     * @param $data
     * @return mixed
     */
    public static function add($guid, $data)
    {
        $yac = new \Yac\YacCache(self::getPrefix());
        return $yac->set($guid, $data);
    }


    /**
     * 更新用户信息
     *
     * @param $guid
     * @param array $arr
     * @return bool|mixed
     */
    public static function set($guid, array $arr)
    {
        if (!$guid || !is_array($arr)) {
            return false;
        }

        $res = self::get($guid);
        $new = array_merge($res, $arr);

        return self::add($guid, $new);
    }


}
