<?php
/**
 * @author JKD
 * @date 2021年08月03日 23:50
 */

namespace app\services\Api;


class Index
{

    protected $JkdRequest;

    public function __construct($request = '')
    {
        $this->JkdRequest = $request;
    }

    public function index()
    {
        \JkdResponse::Success($this->JkdRequest ?: 'Hello JkdYaf !');
    }
}