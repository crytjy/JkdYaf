<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace UnAutoLoader;

trait JkdRequest
{
    protected $jkdYafParams;

    public function __construct()
    {
        $this->jkdYafParams = getJkdYafParams('JKDYAF_PARAMS');
    }
}
