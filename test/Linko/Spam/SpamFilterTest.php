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

        $this->spam = new SpamFilter();
        $this->spam->registerDetector($blackList);
    }

    public function testRegisteredDetectors()
    {
        $detectors = $this->spam->getDetectors();
        $this->assertContainsOnlyInstancesOf('Linko\Spam\SpamDetectorInterface', $detectors);
        $this->assertArrayHasKey("BlackList", $detectors);
        $this->assertFalse($this->spam->getDetector('Dummy'));
        $this->assertInstanceOf('Linko\Spam\SpamDetectorInterface', $this->spam->getDetector('BlackList'));
    }

    public function tearDown()
    {
        $this->spam = null;
    }
}