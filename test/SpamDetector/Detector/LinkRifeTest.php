<?php namespace SpamDetector\Detector;

use SpamDetector\SpamDetector;

class LinkRifeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SpamDetector
     */
    protected $spam;

    public function setUp()
    {
        $linkRifeDetector = new LinkRife();
        $linkRifeDetector->setMaxLinkAllowed(2);
        $linkRifeDetector->setMaxRatio(30);

        $this->spam = new SpamDetector();
        $this->spam->registerDetector($linkRifeDetector);
    }

    public function testLinkFreeContent()
    {
        $string = "This is a very clean text with no links in it.";
        $this->assertTrue($this->spam->check($string)->passed());
        $this->assertFalse($this->spam->check($string)->failed());
    }

    public function testMaxLinksAllowed()
    {
        $string = "Hello, today i am going to teach you how to train a dragon...
            This link should be long so that the maximum ratio of links is less than
            the number of words. But i will add two links here www.example.com
            and here site.com which will cause the spam detector to detect is as
            spam because we set that the maximum number of links allowed is 2 using
            the setMaxLinkAllowed(...) above.";

        $this->assertTrue($this->spam->check($string)->failed());
    }

    public function testMaxLinkRatio()
    {
        $string = "Yep, http://this.com is a http://string.com visit my site.com
        and this spam.net should fail.org becos of mywife@home.com
        contains more.info links than.com overall http://words.ly beating the ratio.com record.net
        don't mind me advertis.in these sit.es do.you?";

        $this->assertTrue($this->spam->check($string)->failed());
    }

    public function tearDown()
    {
        $this->spam = null;
    }
}