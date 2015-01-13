<?php
namespace PhantomJS;

use \PHPUnit_Framework_TestCase as TestCase;
abstract class AbstractAssert {
  private $name;
  private $message;
  
  public function __construct($name, $message) {
    $this->name = $name;
  }
  public function getName() {
    return $this->name;
  }
  public function getMessage() {
      $msg = $this->name;
      if ($this->message) {
          $msg .= ":" . $this->message;
      }
      return $msg;
  }
  
  public abstract function getHarvester();
  
  public abstract function evaluate(TestCase $testCase, $harvestedValue);
}