<?php

namespace Mattpatt\PHPUnitFocus;

class SourceFile
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
        $this->tokens = new \PHP_Token_Stream($file);
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getTestForLine($line)
    {
        foreach ($this->tokens->getClasses() as $className => $class) {
            foreach ($class['methods'] as $methodName => $method) {
                if (!static::isTestMethod($methodName, $method)) {
                    continue;
                }
                if ($method['startLine'] > $line || $method['endLine'] < $line) {
                    continue;
                }
                if ($class['package']['namespace']) {
                    return sprintf('%s\%s::%s', $class['package']['namespace'], $className, $methodName);
                } else {
                    return sprintf('%s::%s', $className, $methodName);
                }
            }
        }
    }

    public function isValid()
    {
        return $this->hasTestClasses();
    }

    private function hasTestClasses()
    {
        foreach ($this->tokens->getClasses() as $class => $_) {
            if (substr($class, -4) === 'Test') {
                return true;
            }
        }
        return false;
    }

    private static function isTestMethod($methodName, $method)
    {
        return (substr($methodName, 0, 4) === 'test')
            || (strpos($method['docblock'], '@test') !== false);
    }
}
