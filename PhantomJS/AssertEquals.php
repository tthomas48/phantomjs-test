<?php
namespace PhantomJS;

use \PHPUnit_Framework_TestCase as TestCase;

class AssertEquals extends AbstractAssert
{

    private $condition;
    private $expectedValue;

    public function __construct($name, $condition, $expectedValue, $message = "")
    {
        parent::__construct($name, $message);
        $this->condition = $condition;
        $this->expectedValue = $expectedValue;
    }

    public function getHarvester()
    {
        return json_encode($this->getName()) . ":" . $this->condition;
    }

    public function evaluate(TestCase $testCase, $harvestedValue)
    {
        $testCase->assertEquals($this->expectedValue, $harvestedValue, $this->getMessage());
    }
}