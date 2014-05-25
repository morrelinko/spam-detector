<?php

namespace SpamDetector\Detector;

class LinkRifeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LinkRife
     */
    protected $linkRife;

    public function setUp()
    {
        $linkRifeDetector = new LinkRife();
        $linkRifeDetector->setMaxLinkAllowed(2);
        $linkRifeDetector->setMaxRatio(30);

        $this->linkRife = $linkRifeDetector;
    }

    public function testLinkFreeContent()
    {
        $data = array(
            'text' => 'This is a very clean text with no links in it.'
        );

        $this->assertFalse($this->linkRife->detect($data));
    }

    public function testMaxLinksAllowed()
    {
        $data = array(
            'text' => 'Hello, today i am going to teach you how to train a dragon...
                       This link should be long so that the maximum ratio of links is less than
                       the number of words. But i will add two links here www.example.com
                       and here site.com which will cause the spam detector to detect is as
                       spam because we set that the maximum number of links allowed is 2 using
                       the setMaxLinkAllowed(...) above.'
        );

        $this->assertTrue($this->linkRife->detect($data));
    }

    public function testMaxLinkRatio()
    {
        $data = array(
            'text' => 'Yep, http://this.com is a http://string.com visit my site.com
                        and this spam.net should fail.org because of my-wife@home.com
                        contains more.info links than.com overall
                        http://words.ly beating the ratio.com record.net
                        don\'t mind me advertis.in these sit.es doy.ou?'
        );

        $this->assertTrue($this->linkRife->detect($data));
    }

    public function tearDown()
    {
        $this->linkRife = null;
    }
}