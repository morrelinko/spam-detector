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
        $this->assertArrayHasKey("Linko\\Spam\\Detector\\BlackList", $detectors);
    }

    public function tearDown()
    {
        $this->spam = null;
    }
}