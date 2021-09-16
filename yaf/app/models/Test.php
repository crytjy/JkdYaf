<?php

class TestModel extends JkdBaseModel
{
    protected $table = 'users';
    protected $fillAble = ['id', 'name', 'password'];
    protected $selectAble = ['id', 'name'];
}