<?php

function test_autoload($className) {
  $path = dirname ( __FILE__ ) . "/../";


  if(strpos($className, '\\') === false) {
    $pieces = explode("_", $className);
  } else {
    $pieces = explode('\\', $className);
  }
  $classPath = implode("/", $pieces);

  if (file_exists($path . $classPath . '.php')) {
    require_once $classPath . '.php';
    return true;
  }
  return false;
}

spl_autoload_register('test_autoload');
