<?php
/**
 * Aop
 *
 * Class AOP
 */

namespace Aop;

class JkdAop
{
    /**
     * @var JkdAop
     */
    private static $instance;

    private static $aopList;

    /**
     * Get the instance of JkdAop.
     *
     * @return JkdAop
     */
    public static function get()
    {
        if (!self::$instance) {
            self::$instance = new JkdAop();
        }
        return self::$instance;
    }


    /**
     * 获取AOP列表
     *
     * @throws \ReflectionException
     */
    public function getAopParser()
    {
        $yafRequest = \Yaf\Registry::get('YAF_HTTP_REQUEST');
        $moduleName = $yafRequest->module ?? '';
        $controllerName = $yafRequest->controller ?? '';
        $className = $controllerName . 'Controller' ?? '';
        $functionName = $yafRequest->action . 'Action' ?? '';

        \Yaf\Loader::import(APP_PATH . '/app/modules/' . $moduleName . '/controllers/' . $controllerName . '.php');
        $ref = new \ReflectionMethod($className, $functionName);
        $doc = $ref->getDocComment();
        $docParser = new DocParser();
        self::$aopList = $docParser->parse($doc);

        return true;
    }


    /**
     * 启动AOP
     *
     * @param $type
     * @return bool
     */
    public function runAop($type)
    {
        $list = self::$aopList[$type] ?? [];
        if ($list) {
            foreach ($list as $li) {
                $thisClass = $li['class'] ?? '';
                $thisFunction = $li['function'] ?? '';
                if ($thisClass && $thisFunction) {
                    $thisInstance = new $thisClass();
                    $thisInstance->$thisFunction();
                }
            }
        }

        return true;
    }

}
