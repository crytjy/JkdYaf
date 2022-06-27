<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Jkd;

class JkdBaseController extends \Yaf\Controller_Abstract
{
    protected $JkdService;

    public function init($isAuto = true)
    {
        if ($isAuto) {
            $thisService = getService();
            $this->JkdService = new $thisService();
        }
    }
}
