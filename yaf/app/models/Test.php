<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
class TestModel extends \Jkd\JkdBaseModel
{
    protected $table = 'users';

    protected $fillAble = ['id', 'name', 'password'];

    protected $selectAble = ['id', 'name'];

    protected $isCache = 1;

    protected $cacheKey = 'Users';

    protected $primaryKey = 'id';

    protected $foreignKey = 'id';

    protected $isList = 0;

    protected $hasListKey = 1;
}
