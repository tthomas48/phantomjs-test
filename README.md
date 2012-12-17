phantomjs-test
==============

Allows PHPUnit to test using PhantomJS.

A Basic Test
------------
    class BasicTest extends \PHPUnit_Framework_TestCase {
      public function setUp() {
        $this->driver = new \PhantomJS\Driver($this);
      }
      public function testPage() {
        $html = "<html><body><h1>Hi</h1><script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js\"></script></body></html>";

        $this->driver->assertTrue("h1 exists", "$('h1').length == 1");
        $this->driver->assertTrue("h1 value is Hi", "$('h1').html() == 'Hi'");
        $this->driver->test($html);
      }
    }
    
