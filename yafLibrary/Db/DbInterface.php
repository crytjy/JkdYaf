<?php

namespace Db;

/*
 * Db接口定义
 */

interface DbInterface
{

    static public function getInstance(); //要求所有数据连接皆为单例

    function execute($query); //执行sql语句

    function transaction($query); //事务

    function getOne($query); //执行sql语句，获取单个字段数据

    function getRow($query); //从结果集中取得一行作为关联数组

    function getCol($query); //从结果集中取得一列作为关联数组

    function getAll($query); //返回一个N行N列的结果集

    function insert($table, $data); //返回上一次插入记录的ID;

    function update($table, $data, $where); //更新

    function delete($table, $where); //删除

    function close(); //关闭数据库连接
}