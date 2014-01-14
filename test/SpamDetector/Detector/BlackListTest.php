<?php namespace SpamDetector\Detector;

use SpamDetector\SpamDetector;

class BlackListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SpamDetector
     */
    protected $spam;

    /**
     * @var BlackList
     */
    protected $blackList;

    public function setUp()
    {
        $this->blackList = new BlackList();

        // Adding black lists manually
        $this->blackList->add("example.com");
        $this->blackList->add("127.0.0.1");
        $this->blackList->add("[site|some]dump(.*)?\.[com|org|net|info]", true);

        $this->spam = new SpamDetector();
        $this->spam->registerDetector($this->blackList);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSetListFileException()
    {
        $this->blackList->setListFile('some-wrong-list-file.txt');
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

    public function testBannedWordsFromListFile()
    {
        // Setting a file to load black lists from
        $this->blackList->setListFile(__DIR__ . '/Resource/banned.txt');

        $string = "this is a string with banned words loaded from the lis file. [localhost]";
        $this->assertTrue($this->spam->check($string)->failed());
    }

    public function tearDown()
    {
        $this->spam = null;
    }
}