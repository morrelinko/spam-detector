<?php

namespace SpamDetector\Detector;

class BlackListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BlackList
     */
    protected $blackList;

    public function setUp()
    {
        $this->blackList = new BlackList();

        // Adding black lists manually
        $this->blackList->add('example.com');
        $this->blackList->add('127.0.0.1');
        $this->blackList->add('[site|some]dump\.[com|org|net|info]', true);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testSetInvalidListFile()
    {
        $this->blackList->setListFile('some-wrong-list-file.txt');
    }

    public function testSpamFreeString()
    {
        $data = array('text' => 'This is a very clean text with no blacklisted data.');

        $this->assertFalse($this->blackList->detect($data));
    }

    public function testSimpleBlacklistedString()
    {
        $data = array('text' => 'this is a string with example.com and should fail.');
        $this->assertTrue($this->blackList->detect($data));

        $data = array('text' => 'this is a string with 127.0.0.1 and should fail.');
        $this->assertTrue($this->blackList->detect($data));
    }

    public function testRegexPatternBlacklistedString()
    {
        $data = array('text' => 'this is a string with somedump.com should fail spam check');
        $this->assertTrue($this->blackList->detect($data));

        $data = array('text' => 'this is a string with sitedump.net should fail spam check');
        $this->assertTrue($this->blackList->detect($data));
    }

    public function testBannedWordsFromListFile()
    {
        // Setting a file to load black lists from
        $this->blackList->setListFile(__DIR__ . '/Resource/banned.txt');

        $data = array('text' => 'this is a string with banned words loaded from the list file. [localhost]');
        $this->assertTrue($this->blackList->detect($data));
    }

    public function tearDown()
    {
        $this->blackList = null;
    }
}