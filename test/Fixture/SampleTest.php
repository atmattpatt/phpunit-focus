<?php

class SampleTest extends \PHPUnit_Framework_TestCase
{
    public function testOne()
    {
        $this->assertTrue(true);
    }

    public function testTwo()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function otherTest()
    {
        $this->assertTrue(true);
    }

    public function helper()
    {
    }
}
