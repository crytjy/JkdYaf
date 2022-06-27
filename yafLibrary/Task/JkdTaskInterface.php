<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Task;

interface JkdTaskInterface
{
    /**
     * 处理逻辑.
     *
     * @param $params
     * @return mixed
     */
    public function handle($params);
}
