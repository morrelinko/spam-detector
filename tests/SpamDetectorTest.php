<?php namespace SpamDetector;

use SpamDetector\Detector\BlackList;

class SpamDetectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SpamDetector
     */
    protected $spam;

    public function setUp()
    {
        $blackList = new BlackList();

        $this->spam = new SpamDetector();
        $this->spam->registerDetector($blackList);
    }

    public function testRegisteredDetectors()
    {
        $detectors = $this->spam->getDetectors();
        $this->assertContainsOnlyInstancesOf('SpamDetector\Detector\SpamDetectorInterface', $detectors);
        $this->assertArrayHasKey("BlackList", $detectors);

        $this->assertFalse($this->spam->getDetector('Dummy'));
        $this->assertInstanceOf('SpamDetector\Detector\SpamDetectorInterface', $this->spam->getDetector('BlackList'));
    }

    public function tearDown()
    {
        $this->spam = null;
    }
}