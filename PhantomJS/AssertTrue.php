<?php
namespace PhantomJS;

use \PHPUnit_Framework_TestCase as TestCase;

class AssertTrue extends AbstractAssert
{

    private $condition;

    public function __construct($name, $condition, $message = "")
    {
        parent::__construct($name, $message);
        $this->condition = $condition;
    }

    public function getHarvester()
    {
        return json_encode($this->getName()) . ":" . $this->condition;
    }

    public function evaluate(TestCase $testCase, $harvestedValue)
    {
        $testCase->assertTrue($harvestedValue, $this->getMessage());
    }
}