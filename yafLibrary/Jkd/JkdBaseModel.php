<?php
/**
 * 模型类
 *
 * Class JkdBaseModel
 */

namespace Jkd;

use Cache\Redis;
use Db\BaseModel;

class JkdBaseModel extends BaseModel
{

    protected $db;
    protected $table;                       //表名
    protected $fillAble = [];               //填充字段
    protected $selectAble = [];             //查找字段
    protected $isCache = 0;                 //是否开启缓存 0：否 1：是
    protected $cacheKey = '';               //缓存key
    protected $cacheExpire = 86400;         //缓存时间
    protected $primaryKey = 'id';           //主键
    protected $foreignKey = '';             //外键
    protected $isList = 1;                  //是否列表数据
    protected $hasListKey = 1;              //是否包含列表key
    private $cacheAllKey = '';              //缓存key-所有数据

    public function __construct(string $dbName = '')
    {
        $this->cacheKey = $this->cacheKey ?? $this->table;
        $this->cacheAllKey = $this->cacheKey . ':ALL';
        parent::__construct($dbName);
    }

    private function getCacheKey($foreignValue)
    {
        $this->cacheKey = $this->cacheKey . ':' . $foreignValue;
    }

    /**
     * 获取缓存数据
     *
     * @param string|int $foreignValue //外键值
     * @param string $whereKey //条件key
     * @param string|int $whereValue //条件值
     * @return array|mixed|string
     */
    public function getCache($foreignValue = 'ALL', string $whereKey = '', $whereValue = '')
    {
        if (!$this->isCache) {
            return '';
        }

        $redisPool = new Redis();
        $redis = $redisPool->get();
        $this->getCacheKey($foreignValue);
        if ($foreignValue == 'ALL') {
            $this->isList = 1;  //查询全部时强制转换成列表
            $foreignWhere = [];
        } else {
            $foreignWhere = [$this->foreignKey => $foreignValue];
        }

        if ($this->isList) {
            $res = $this->getVoList($redis, $foreignWhere, $whereKey, $whereValue);
        } else {
            $res = $this->getVo($redis, $foreignWhere);
        }

        $redisPool->put();
        return $res ?? '';
    }


    /**
     * Get Model Vo
     *
     * @param $redis
     * @param $foreignWhere
     * @return mixed|string
     */
    public function getVo($redis, $foreignWhere)
    {
        $res = $redis->get($this->cacheKey) ?? '';
        if ($res) {
            $res = json_decode($res, true);
        } else {
            $res = parent::get($foreignWhere);
            if ($res) {
                $redis->set($this->cacheKey, json_encode($res));
                if ($redis->ttl($this->cacheKey) == -1) {
                    $redis->expire($this->cacheKey, $this->cacheExpire);
                }
            }
        }

        return $res;
    }


    /**
     * Get Model VoList
     *
     * @param $redis
     * @param $foreignWhere
     * @param string $whereKey
     * @param string $whereValue
     * @return array|mixed|string
     */
    public function getVoList($redis, $foreignWhere, string $whereKey = '', $whereValue = '')
    {
        $res = $redis->hGetAll($this->cacheKey) ?? [];
        if (!$res) {
            $res = parent::all($foreignWhere);
            if ($res) {
                $whereKey = $whereKey ?: $this->primaryKey;
                $res = array_column($res, null, $whereKey);
                $newRes = [];
                foreach ($res as $re) {
                    $newRes[$re[$whereKey]] = json_encode($re);
                }
                $res = $newRes;
                $newRes = null;
                $redis->hmset($this->cacheKey, $res);
                if ($redis->ttl($this->cacheKey) == -1) {
                    $redis->expire($this->cacheKey, $this->cacheExpire);
                }
            }
        }
        if ($whereKey && $whereValue) {
            $res = $res[$whereValue] ?? '';
            $res = $res ? json_decode($res, true) : [];
        } else {
            foreach ($res as $i => $re) {
                $res[$i] = json_decode($re, true);
            }

            if ($this->hasListKey != 1) {
                sort($res);
            }
        }

        return $res;
    }


    /**
     * 新增
     *
     * @param array $data 添加数组
     * @param false $isFillFilter 是否过滤数组
     * @return bool|string
     */
    public function insert(array $data, $isFillFilter = false)
    {
        $insertId = $this->insertSql($data, $isFillFilter);
        if ($insertId && isset($data[$this->foreignKey])) {
            $this->delCache($data[$this->foreignKey]);
        }
        return $insertId;
    }


    /**
     * 更新
     *
     * @param array $data 更新数组
     * @param array $where 条件
     * @param false $isFillFilter 是否过滤数组
     * @param string|int $foreignValue
     * @return bool
     */
    public function update(array $data, array $where = [], $isFillFilter = false, $foreignValue = '')
    {
        $updateId = $this->updateSql($data, $where, $isFillFilter);

        if ($updateId && $foreignValue) {
            $this->delCache($foreignValue);
        }

        return $updateId;
    }


    /**
     * 删除
     *
     * @param $where
     * @param string|int $foreignValue
     * @return mixed
     */
    public function delete($where, $foreignValue = '')
    {
        $deleteId = $this->deleteSql($where);

        if ($deleteId && $foreignValue) {
            $this->delCache($foreignValue);
        }

        return $deleteId;
    }


    /**
     * 清除缓存
     */
    public function delCache($foreignValue = '', $key = '')
    {
        if (!$this->isCache) {
            return '';
        }

        $this->getCacheKey($foreignValue);
        $redisPool = new Redis();
        $redis = $redisPool->get();
        if ($key) {
            $redis->hdel($this->cacheKey, $key);
        } else {
            $redis->del($this->cacheKey);
        }
        //清除所有数据ALL
        $redis->del($this->cacheAllKey);
        $redisPool->put();

        return true;
    }

}
