<?php

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
    protected $hasListKey = 0;
}
