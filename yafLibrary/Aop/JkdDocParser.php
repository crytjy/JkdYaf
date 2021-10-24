<?php
/**
 * 獲取AOP注解
 */

namespace Aop;

class DocParser
{
    private $params = [
        'AopBefore' => [],
        'AopAfter' => [],
        'AopAround' => [],
    ];


    public function parse($doc = '')
    {
        if ($doc == '') {
            return $this->params;
        }
        // Get the comment
        if (preg_match('#^/\*\*(.*)\*/#s', $doc, $comment) === false)
            return $this->params;

        $comment = trim($comment[1]);
        // Get all the lines and strip the * from the first character
        if (preg_match_all('#^\s*\*(.*)#m', $comment, $lines) === false)
            return $this->params;

        $this->parseLines($lines[1]);
        return $this->params;
    }


    private function parseLines($lines)
    {
        foreach ($lines as $line) {
            foreach ($this->params as $aopType => $param) {
                if (strpos($line, $aopType) !== false) {
                    preg_match_all('/\(([\s\S]*?),/', $line, $className);
                    preg_match_all('/,([\s\S]*?)\)/', $line, $functionName);

                    if (isset($className[1][0]) && $className[1][0] && isset($functionName[1][0]) && $functionName[1][0]) {
                        $this->params[$aopType][] = [
                            'class' => trim($className[1][0]),
                            'function' => trim($functionName[1][0]),
                        ];
                    }
                }
            }
        }

        return $this->params;
    }

}