<?php

class TestModel extends \Jkd\JkdBaseModel
{
    protected $table = 'users';
    protected $fillAble = ['id', 'name', 'password'];
    protected $selectAble = ['id', 'name'];
}