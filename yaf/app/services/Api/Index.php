<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace app\services\Api;

use Jkd\JkdBaseService;
use Jkd\JkdResponse;

class Index extends JkdBaseService
{
    public function index()
    {
        return JkdResponse::Success('Hello JKD !');
    }
}
