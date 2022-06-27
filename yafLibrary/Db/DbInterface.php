<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Db;

/*
 * Db接口定义
 */

interface DbInterface
{
    public static function getInstance(); // 要求所有数据连接皆为单例

    public function execute($query); // 执行sql语句

    public function transaction($query); // 事务

    public function getOne($query); // 执行sql语句，获取单个字段数据

    public function getRow($query); // 从结果集中取得一行作为关联数组

    public function getCol($query); // 从结果集中取得一列作为关联数组

    public function getAll($query); // 返回一个N行N列的结果集

    public function insert($table, $data); // 返回上一次插入记录的ID;

    public function update($table, $data, $where); // 更新

    public function delete($table, $where); // 删除

    public function close(); // 关闭数据库连接
}
