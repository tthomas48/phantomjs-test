<?php
namespace PhantomJS;
class AssertExec extends AssertTrue {
  private $command;
  
  public function __construct($name, $command, $message = "") {
      parent::__construct($name, $message);
    $this->command = $command;
  }  
  
  public function getHarvester() {
      
        $output = "function() { var exception = undefined;\n";
        $output .= "try {\n";
        $output .= $this->command;
        $output .= "} catch(e) {\n";
        $output .= "exception = e;\n";
        $output .= "}\n return exception === undefined; }";
        return json_encode($this->getName()) . ":" . $output;
  }
    
}