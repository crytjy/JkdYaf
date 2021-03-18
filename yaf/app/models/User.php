<?php

/**
 * 用户类
 *
 * @name UserModel
 */

class UserModel extends Base
{

    protected $table = 'user';

    protected $fillAble = '*';

    /**
     * 获取用户信息
     *
     * @param $guid
     * @param $values
     * @return int
     */
    public function getUser($guid, $values = '')
    {
        $sql = 'select ' . ($values ?: $this->fillAble) . ' from ' . $this->table . ' where id = ' . $guid;
        $res = $this->db->getRow($sql);
        return $res ?? [];
    }


    public function getAllUser()
    {
        $sql = 'select * from ' . $this->table;
        $res = $this->db->getAll($sql);

        return $res;
    }

}
