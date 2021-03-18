<?php

/**
 * 模型类
 *
 * Class Base
 */

use \Db\Factory;

class Base
{
    protected $db;

    public function __construct()
    {
//        $config = \JkdConf::get('db');
        $config = Yaf\Registry::get('config')->db;
        $this->db = Factory::create($config);
    }

}
