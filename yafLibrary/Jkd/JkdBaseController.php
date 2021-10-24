<?php
/**
 * 控制器
 *
 * Class JkdBaseController
 */
namespace Jkd;

class JkdBaseController extends \Yaf\Controller_Abstract
{
    protected $JkdService;

    public function init($isAuto = true)
    {
        if ($isAuto) {
            $thisService = getService();
            $this->JkdService = new $thisService;
        }
    }



}
