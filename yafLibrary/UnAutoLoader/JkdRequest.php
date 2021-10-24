<?php
/**
 * JkdRequest
 */
namespace UnAutoLoader;

trait JkdRequest
{
    protected $JkdRequest;

    public function __construct()
    {
        $this->JkdRequest = \Yaf\Registry::get('REQUEST_PARAMS');
    }


    /**
     * 更新JkdRequest
     *
     * @param $data
     */
    public function setRequest($data)
    {
        \Yaf\Registry::set('REQUEST_PARAMS', $data);
        $this->JkdRequest = $data;
    }


    /**
     * 追加JkdRequest
     *
     * @param $key
     * @param $value
     */
    public function appendRequest($key, $value)
    {
        $this->JkdRequest[$key] = $value;
        \Yaf\Registry::set('REQUEST_PARAMS', $this->JkdRequest);
    }

}
