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

class BaseModel
{
    protected $db;

    protected $table;

    protected $fillAble = [];

    protected $selectAble = [];

    public function __construct(string $dbName = '')
    {
        if ($dbName) {
            $this->db = Factory::create($dbName);
        } else {
            $this->db = Factory::getPool();
        }
    }

    /**
     * 获取sql语句与数值
     *
     * @param array $where
     * @param array $select
     * @param string $order
     * @param string $sort
     * @return array
     */
    public function dumpSql($where = [], $select = [], $order = '', $sort = '')
    {
        if (! $select) {
            $selectAble = $this->selectAble ? '`' . implode('`, `', $this->selectAble) . '`' : '*';
        } else {
            $selectAble = implode('`, `', $select);
        }
        $values = [];
        $sql = 'SELECT ' . $selectAble . ' FROM `' . $this->table . '`';
        if ($where) {
            $wheres = '';
            foreach ($where as $c => $v) {
                $wheres .= ($wheres ? 'AND' : '') . " `{$c}` = ? ";
                $values[] = $v;
            }
            $sql .= ' WHERE' . $wheres;
        }

        if ($order && $sort) {
            $sql .= 'ORDER BY ' . $order . ' ' . $sort;
        }

        return [$sql, $values];
    }

    /**
     * 获取一条数据数据.
     *
     * @param array $where
     * @param array $select
     * @param string $order
     * @param string $sort
     * @return mixed|string
     */
    public function get($where = [], $select = [], $order = '', $sort = '')
    {
        [$sql, $values] = $this->dumpSql($where, $select, $order, $sort);

        $res = $this->db->getRow($sql, $values);
        return $res ?? '';
    }

    /**
     * 获取所有数据.
     *
     * @param array $where
     * @param array $select
     * @param string $order
     * @param string $sort
     * @return mixed|string
     */
    public function all($where = [], $select = [], $order = '', $sort = '')
    {
        [$sql, $values] = $this->dumpSql($where, $select, $order, $sort);
        $res = $this->db->getAll($sql, $values);
        return $res ?? '';
    }

    /**
     * 新增.
     *
     * @param array $data 添加数组
     * @param false $isFillFilter 是否过滤数组
     * @return bool|string
     */
    public function insertSql(array $data, $isFillFilter = false)
    {
        return $this->db->insert($this->table, $isFillFilter == true ? array_only($data, $this->fillAble) : $data);
    }

    /**
     * 更新.
     *
     * @param array $data 更新数组
     * @param array $where 条件
     * @param false $isFillFilter 是否过滤数组
     * @return bool
     */
    public function updateSql(array $data, array $where = [], $isFillFilter = false)
    {
        return $this->db->update($this->table, $isFillFilter == true ? array_only($data, $this->fillAble) : $data, $where);
    }

    /**
     * 删除.
     *
     * @return bool
     */
    public function deleteSql(array $where = [])
    {
        return $this->db->delete($this->table, $where);
    }
}
