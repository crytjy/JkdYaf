<?php
/**
 * This file is part of JkdYaf.
 *
 * @Product  JkdYaf
 * @Github   https://github.com/crytjy/JkdYaf
 * @Document https://jkdyaf.crytjy.com
 * @Author   JKD
 */
namespace Aop;

use Route\JkdRoute;

class JkdAop
{
    public const AopType = [
        'AopBefore',
        'AopAfter',
        'AopAround',
    ];

    /**
     * @var JkdAop
     */
    private static $instance;

    /**
     * Get the instance of JkdAop.
     *
     * @return JkdAop
     */
    public static function get()
    {
        if (! self::$instance) {
            self::$instance = new JkdAop();
        }
        return self::$instance;
    }

    /**
     * 获取Aop.
     *
     * @param $reflection
     */
    public function getAttributeData($reflection): array
    {
        $jkdAop = [];
        $attributes = $reflection->getAttributes();
        foreach ($attributes as $attribute) {
            $aopType = $attribute->getName();
            if (in_array($aopType, self::AopType)) {
                $jkdAop[$aopType][] = $attribute->getArguments();
            }
        }

        return $jkdAop;
    }

    /**
     * 获取AOP列表.
     *
     * @param $moduleName
     * @param $controllerName
     * @param $actionName
     * @throws \ReflectionException
     * @return array
     */
    public function getAopParser($moduleName, $controllerName, $actionName)
    {
        $className = $controllerName . 'Controller' ?? '';
        $functionName = $actionName . 'Action' ?? '';

        \Yaf\Loader::import(APP_PATH . '/app/modules/' . $moduleName . '/controllers/' . $controllerName . '.php');

        $ref = new \ReflectionMethod($className, $functionName);

        return $this->getAttributeData($ref);
    }

    /**
     * 启动AOP.
     *
     * @param $type
     * @return bool
     */
    public function runAop($type)
    {
        $list = JkdRoute::get()->getRouteType('aop')[$type] ?? [];
        foreach ($list as $li) {
            $thisClass = $li[0] ?? '';
            $thisFunction = $li[1] ?? '';
            if ($thisClass && $thisFunction) {
                $thisInstance = new $thisClass();
                $thisInstance->{$thisFunction}();
            }
        }

        return true;
    }
}
