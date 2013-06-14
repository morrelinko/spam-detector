<?php namespace Linko\Spam;

use Linko\Spam\Detector\BlackList;

class SpamFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SpamFilter
     */
    protected $spam;

    public function setUp()
    {
        $blackList = new BlackList();
        $blackList->add("example.com");
        $blackList->add("127.0.0.1");
        $blackList->add("[site|some]dump(.*)?\.[com|org|net|info]", true);

        $this->spam = new SpamFilter();
        $this->spam->registerDetector($blackList);
    }

    public function testRegisteredDetectors()
    {
        $detectors = $this->spam->getDetectors();
        $this->assertContainsOnlyInstancesOf('Linko\Spam\SpamDetectorInterface', $detectors);
        $this->assertArrayHasKey("Linko\\Spam\\Detector\\BlackList", $detectors);
    }

    public function testSpamFreeString()
    {
        $string = "This is a very clean text with no blacklisted data.";
        $this->assertTrue($this->spam->check($string)->passed());
        $this->assertFalse($this->spam->check($string)->failed());
    }

    public function testSimpleBlacklistedString()
    {
        $string = "this is a string with example.com";
        $this->assertTrue($this->spam->check($string)->failed());
        $this->assertFalse($this->spam->check($string)->passed());

        $string = "this is a string with 127.0.0.1";
        $this->assertTrue($this->spam->check($string)->isSpam());
    }

    public function testRegexPatternBlacklistedString()
    {
        $string = "this is a string with somedump.com should fail spam check";
        $this->assertTrue($this->spam->check($string)->failed());

        $string = "this is a string with sitedump.net should fail spam check";
        $this->assertTrue($this->spam->check($string)->isSpam());
    }

    public function tearDown()
    {
        $this->spam = null;
    }
}