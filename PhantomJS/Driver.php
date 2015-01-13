<?php
namespace PhantomJS;

class Driver
{

    private $tests;

    private $testcase;

    public function __construct($testcase)
    {
        $this->testcase = $testcase;
        $this->reset();
    }

    public function reset()
    {
        $this->tests = [];
    }

    public function assertTrue($name, $condition, $message = "")
    {
        $this->tests[] = new AssertTrue($name, $condition, $message);
    }

    public function assertEquals($name, $value, $condition, $message = "")
    {
        $this->tests[] = new AssertEquals($name, $condition, $value, $message);
    }

    public function exec($name, $exec, $message = "")
    {
        $this->tests[] = new AssertExec($name, $exec, $message);
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
                    var tests = {";
        
        $harvested = [];
        foreach ($this->tests as $test) {
            $harvested[] = $test->getHarvester();
        }
        $output .= implode(",", $harvested);
        $output .= "};\nreturn tests;});";
        $output .= "console.log(JSON.stringify(ua));";
        $output .= "
            } else {
                console.log({error: 'page returned ' + status + '.'});
            }
            } catch(e) {
            console.log({error: e});
            }
            phantom.exit();
        };";
        $output_file = tempnam("/tmp/", "pjs");
        file_put_contents($output_file, $output);
        $stdout = shell_exec("phantomjs " . $output_file);
        $results = json_decode($stdout);
        
        for ($i = 0; $i < count($this->tests); $i ++) {
            $test = $this->tests[$i];
            $name = $test->getName();
            $value = $results->$name;
            $test->evaluate($this->testcase, $value);
        }
        unlink($output_file);
    }
}