<?php

namespace Mattpatt\PHPUnitFocus;

class SourceFileTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $sourceFile = new SourceFile($this->sampleTestFile());

        $this->assertEquals($this->sampleTestFile(), $sourceFile->getFile());
    }

    public function testIsValidForPHPUnitTest()
    {
        $sourceFile = new SourceFile($this->sampleTestFile());
        $this->assertTrue($sourceFile->isValid());
    }

    public function testIsNotValidForOtherFiles()
    {
        $sourceFile = new SourceFile($this->sampleMiscFile());
        $this->assertFalse($sourceFile->isValid());
    }

    /**
     * @dataProvider provideLineAndExpectedTest
     */
    public function testGetTestForLine($line, $expectedTest)
    {
        $sourceFile = new SourceFile($this->sampleTestFile());
        $this->assertEquals(
            $expectedTest,
            $sourceFile->getTestForLine($line)
        );
    }

    /**
     * @dataProvider provideLineAndExpectedTestWithNamespaces
     */
    public function testGetTestForLineWithNamespaces($line, $expectedTest)
    {
        $sourceFile = new SourceFile($this->sampleNamespaceTestFile());
        $this->assertEquals(
            $expectedTest,
            $sourceFile->getTestForLine($line)
        );
    }

    public function testGetTestForLineWorksWithTestAnnotions()
    {
        $sourceFile = new SourceFile($this->sampleTestFile());
        $this->assertEquals(
            "SampleTest::otherTest",
            $sourceFile->getTestForLine(18)
        );
    }

    public function testGetTestForLineReturnsNullWhenNoTestMatches()
    {
        $sourceFile = new SourceFile($this->sampleTestFile());
        $this->assertNull($sourceFile->getTestForLine(9));
    }

    public function testGetTestForLineIgnoresNonTestMethods()
    {
        $sourceFile = new SourceFile($this->sampleTestFile());
        $this->assertNull($sourceFile->getTestForLine(22));
    }

    public function provideLineAndExpectedTest()
    {
        return [
            [5, 'SampleTest::testOne'],
            [6, 'SampleTest::testOne'],
            [7, 'SampleTest::testOne'],
            [8, 'SampleTest::testOne'],
            [10, 'SampleTest::testTwo'],
            [11, 'SampleTest::testTwo'],
            [12, 'SampleTest::testTwo'],
            [13, 'SampleTest::testTwo'],
        ];
    }

    public function provideLineAndExpectedTestWithNamespaces()
    {
        return [
            [7, 'Example\NamespacedTest::testOne'],
            [8, 'Example\NamespacedTest::testOne'],
            [9, 'Example\NamespacedTest::testOne'],
            [10, 'Example\NamespacedTest::testOne'],
        ];
    }

    private function sampleMiscFile()
    {
        return realpath(__DIR__ . '/../../Fixture/SampleMisc.php');
    }

    private function sampleNamespaceTestFile()
    {
        return realpath(__DIR__ . '/../../Fixture/SampleNamespaceTest.php');
    }

    private function sampleTestFile()
    {
        return realpath(__DIR__ . '/../../Fixture/SampleTest.php');
    }
}
