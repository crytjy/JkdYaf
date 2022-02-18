<?php

namespace Task;

interface JkdTaskInterface
{

    /**
     * 处理逻辑
     *
     * @param $params
     * @return mixed
     */
    public function handle($params);

}