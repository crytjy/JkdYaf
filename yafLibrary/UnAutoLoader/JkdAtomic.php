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

use Swoole\Atomic;

trait JkdAtomic
{
    /**
     * start all atomic.
     */
    public function startAtomic()
    {
        $atomicConf = parse_ini_file(CONF_PATH . '/atomic.ini', true);
        if (isset($atomicConf['is_start']) && $atomicConf['is_start'] == 1) {
            unset($atomicConf['is_start']);
            foreach ($atomicConf as $key => $atomic) {
                $_ENV['ATOMIC'][$key] = new Atomic($atomic['INIT_VALUE'] ?? '');
            }
        }
    }
}
