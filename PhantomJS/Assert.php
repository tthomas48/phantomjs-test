<?php
namespace PhantomJS;
class Assert {
  private $name;
  private $condition;
  private $success;
  
  public function __construct($name, $condition) {
    $this->name = $name;
    $this->condition = $condition;
    $this->success = 0;
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
        $output = "tests[tests.length] = {\n";
        $output .= "'name': " . json_encode($this->name) . ",\n";
        $output .= "'pass': " . $this->condition . "\n";
        $output .= "};\n";
        return $output;
  }
}