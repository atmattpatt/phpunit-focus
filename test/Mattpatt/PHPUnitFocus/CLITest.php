<?php

namespace Mattpatt\PHPUnitFocus;

class CLITest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $file = $this->sampleTestFile();
        $argv = [null, 'phpunit', $file, 7];
        $cli = $this->getStubbedCLI($argv);

        $cli->expects($this->once())
            ->method('exec')
            ->with($this->equalTo("phpunit --filter '/SampleTest::testOne/' $file"));

        $cli->run();
    }

    public function testRunDisplaysUsage()
    {
        $argv = [null];
        $cli = $this->getStubbedCLI($argv);

        $cli->expects($this->never())
            ->method('exec');

        $this->expectOutputRegex('/Usage/');

        $cli->run();
    }

    public function testRunPassesThroughAdditionalArguments()
    {
        $file = $this->sampleTestFile();
        $argv = [null, 'phpunit', $file, 7, '--log-tap'];
        $cli = $this->getStubbedCLI($argv);

        $cli->expects($this->once())
            ->method('exec')
            ->with($this->equalTo("phpunit --filter '/SampleTest::testOne/' --log-tap $file"));

        $cli->run();
    }

    private function getStubbedCLI($argv)
    {
        return $this->getMockBuilder('\Mattpatt\PHPUnitFocus\CLI')
            ->setMethods(['exec'])
            ->setConstructorArgs([$argv])
            ->getMock();
    }

    private function sampleTestFile()
    {
        return realpath(__DIR__ . '/../../Fixture/SampleTest.php');
    }
}
