<?php

namespace Mattpatt\PHPUnitFocus;

class FocusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Mattpatt\PHPUnitFocus\CLI::exec
     */
    public function testFocusExecutsPHPUnitTest()
    {
        $focus = realpath(__DIR__ . '/../../../bin/focus');
        $phpunit = realpath(__DIR__ . '/../../../vendor/bin/phpunit');
        $file = $this->sampleTestFile();

        $command = "$focus $phpunit $file 8 --tap";
        $output = null;
        $return = null;

        exec($command, $output, $return);

        $this->assertEquals(0, $return);
        $this->assertContains("phpunit --filter '/SampleTest::testOne/' --tap", $output[0]);
        $this->assertContains('ok 1 - SampleTest::testOne', $output);
    }

    private function sampleTestFile()
    {
        return realpath(__DIR__ . '/../../Fixture/SampleTest.php');
    }
}
