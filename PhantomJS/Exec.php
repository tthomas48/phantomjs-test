<?php
namespace PhantomJS;
class Exec {
  private $name;
  private $exec;
  private $success;
  
  public function __construct($name, $exec) {
    $this->name = $name;
    $this->exec = $exec;
  }  
  
  public function get_name() {
    return $this->name;
  }
  public function set_success($success) {
    $this->success = $success;
  }
  public function is_success() {
    return $this->success;
  }
  public function __toString() {
        $output = "var exception = undefined;\n";
        $output .= "try {\n";
        $output .= $this->exec;
        $output .= "} catch(e) {\n";
        $output .= "exception = e;\n";
        $output .= "}\n";
        $output .= "tests[tests.length] = {\n";
        $output .= "'name': " . json_encode($this->name) . ",\n";
        $output .= "'pass': exception === undefined\n";
        $output .= "};\n";
        return $output;
  }
    
}
