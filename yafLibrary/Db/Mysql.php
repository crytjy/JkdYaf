<?php

namespace Db;

/**
 * mysql操作类
 */
class Mysql implements DbInterface
{

    private static $_instances;
    private $_dbh;
    private $_sth;
    private $_sql;

    private function __construct($dbhost, $dbport, $username, $password, $dbname, $dbcharset)
    {
        try {
            $this->_dbh = new \PDO('mysql:dbname=' . $dbname . ';host=' . $dbhost . ';port=' . $dbport . ';charset=' . $dbcharset, $username, $password, array(\PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
            \JkdLog::error($e->getMessage());
            die();
        }
    }


    static public function getInstance($db_config = '')
    {
        $_db_host = $db_config->host;
        $_db_port = $db_config->port;
        $_db_name = $db_config->dbname;
        $_db_charset = $db_config->charset;
        $_db_usr = $db_config->username;
        $_db_pwd = $db_config->password;

        self::$_instances = new Mysql($_db_host, $_db_port, $_db_usr, $_db_pwd, $_db_name, $_db_charset);
        return self::$_instances;
    }


    function halt($msg = '', $sql = '')
    {
        $error_info = $this->_sth->errorInfo();
        $s = '<pre>';
        $s .= '<b>Error:</b>' . $error_info[2] . '<br />';
        $s .= '<b>Errno:</b>' . $error_info[1] . '<br />';
        $s .= '<b>Sql:</b>' . $this->_sql;

        \JkdLog::error($s);
        die();
    }


    function execute($sql, $values = array())
    {
        $this->_sql = $sql;
        $this->_sth = $this->_dbh->prepare($sql);
        $bool = $this->_sth->execute($values);

        if ('00000' !== $this->_sth->errorCode()) {
            $this->halt();
        }

        return $bool;
    }


    /**
     * 获取全部数据
     *
     * @param $sql
     * @param array $values
     * @param int $fetch_style
     * @return mixed
     */
    function getAll($sql, $values = array(), $fetch_style = \PDO::FETCH_ASSOC)
    {
        $this->execute($sql, $values);
        return $this->_sth->fetchAll($fetch_style);
    }


    /**
     * 获取指定字段
     *
     * @param $sql
     * @param array $params
     * @param int $column_number
     * @return array
     */
    function getCol($sql, $params = array(), $column_number = 0)
    {
        $columns = array();
        $results = array();
        $this->execute($sql, $params);
        $results = $this->_sth->fetchAll(\PDO::FETCH_NUM);
        foreach ($results as $result) {
            $columns[] = $result[$column_number];
        }
        return $columns;
    }


    /**
     * 获取一条数据
     *
     * @param $sql
     * @param array $values
     * @param int $fetch_style
     * @return mixed
     */
    function getRow($sql, $values = array(), $fetch_style = \PDO::FETCH_ASSOC)
    {
        $this->execute($sql, $values);
        return $this->_sth->fetch($fetch_style);
    }


    /**
     * 获取单个字段数据
     *
     * @param $sql
     * @param array $values
     * @param int $column_number
     * @return mixed
     */
    function getOne($sql, $values = array(), $column_number = 0)
    {
        $this->execute($sql, $values);
        return $this->_sth->fetchColumn($column_number);
    }


    /**
     * 新增
     *
     * @param string $table
     * @param array $data
     * @return bool|string
     */
    function insert($table, $data)
    {
        $fields = array_keys($data);
        $marks = array_fill(0, count($fields), '?');

        $sql = "INSERT INTO $table (`" . implode('`,`', $fields) . "`) VALUES (" . implode(", ", $marks) . " )";
        $this->execute($sql, array_values($data));
        $lastInsertId = $this->_dbh->lastInsertId();
        if ($lastInsertId)
            return $lastInsertId;
        else
            return true;
    }


    /**
     * 处理事务
     */
    function transaction($sql)
    {
        try {
            $this->_dbh->beginTransaction();
            $this->_dbh->exec($sql);
            $this->_dbh->commit();
        } catch (PDOException $ex) {
            $this->_dbh->rollBack();
        }
    }


    /**
     * 更新
     *
     * @param string $table
     * @param array $data
     * @param array $where
     * @return bool
     */
    function update($table, $data, $where)
    {
        $values = $bits = $wheres = [];
        foreach ($data as $k => $v) {
            $bits[] = "`$k` = ?";
            $values[] = $v;
        }

        foreach ($where as $c => $v) {
            $wheres[] = "$c = ?";
            $values[] = $v;
        }

        $sql = "UPDATE $table SET " . implode(', ', $bits) . ' WHERE ' . implode(' AND ', $wheres);
        return $this->execute($sql, $values);
    }


    /**
     * 删除
     *
     * @param $table
     * @param $where
     * @return bool
     */
    function delete($table, $where)
    {
        $values = $wheres = array();
        foreach ($where as $key => $val) {
            $wheres[] = "$key = ?";
            $values[] = $val;
        }

        $sql = "DELETE FROM $table WHERE " . implode(' AND ', $wheres);
        return $this->execute($sql, $values);
    }


    /**
     * 关闭连接
     */
    function close()
    {
        unset($this->_instances);
        unset($this->_dbh);
    }

}
