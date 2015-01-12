<?php
namespace PhantomJS;

class Driver
{

    private $tests;

    private $debug;

    private $testcase;

    public function __construct($testcase)
    {
        $this->testcase = $testcase;
        $this->reset();
    }
    
    public function reset()
    {
        $this->tests = [];
        $this->debug = [];
    }

    public function assertTrue($name, $condition)
    {
        $this->tests[] = new Assert($name, $condition);
    }

    public function assertEquals($name, $value, $condition)
    {
        $this->debug[] = "\"'\" + " . json_encode($name) . " + \"':'\" + " . $condition . " + \"'\"";
        $this->tests[] = new Assert($name, "" . json_encode($value) . " == $condition");
    }

    public function exec($name, $exec)
    {
        $this->tests[] = new Exec($name, $exec);
    }

    public function test($page_content)
    {
        $page_content = json_encode($page_content);
        $output = "
            var page = require('webpage').create();
            page.content = $page_content;
            page.onLoadFinished = function(status) {
            try {
            if(status == 'success') {
                var ua = page.evaluate(function () {
                    var tests = [];";
        foreach ($this->tests as $test) {
            $output .= $test->__toString();
        }
        $output .= "return tests;});";
        $output .= "var debug = page.evaluate(function () { var output = ''; ";
            foreach ($this->debug as $debug) {
                $output .= "output += 'DEBUG: ' + " . $debug . ";\n";
            }
        $output .= "return output; });";
        $output .= "console.log(debug);";
        $output .= "
                for(var i = 0, il = ua.length; i < il; i++) {
                    var result = ua[i];
                    console.log(result.name + ' [' + (result.pass ? 'OK' : 'FAIL') + ']');
                }";
        $output .= "
            } else {
                console.log('all [FAIL]');
            }
            } catch(e) {
                console.log(e + ' [FAIL]');
            }
            phantom.exit();
        };";
        $output_file = tempnam("/tmp/", "pjs");
        file_put_contents($output_file, $output);
        $stdout = shell_exec("phantomjs " . $output_file);
        // unlink($output_file);
        
        $lines = explode("\n", $stdout);
        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }
            
            if (preg_match("/DEBUG:(.*)/", $line, $matches)) {
                print $matches[1] . "\n";
                continue;
            }
            
            $found_key = false;
            for ($i = 0; $i < count($this->tests); $i ++) {
                $test = $this->tests[$i];
                if (preg_match('/(.*) \[((?:OK)|(?:FAIL))\]/', $line, $matches)) {
                    if ($test->get_name() == $matches[1]) {
                        $test->set_success($matches[2] == "OK");
                        $this->tests[$i] = $test;
                        $found_key = true;
                    }
                }
            }
            if (! $found_key) {
                $this->testcase->fail($line);
            }
        }
        foreach ($this->tests as $test) {
            $this->testcase->assertTrue($test->is_success(), $test->get_name());
        }
    }
}