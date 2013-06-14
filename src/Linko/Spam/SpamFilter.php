<?php namespace Linko\Spam;

class SpamFilter
{
    /**
     * Holds registed spam detectors
     *
     * @var SpamDetectorInterface[]
     */
    private $_spamDetectors = array();

    /**
     * Checks if a string is spam or not
     *
     * @param $string
     *
     * @return SpamResult
     */
    public function check($string)
    {
        $failure = 0;

        foreach ($this->getDetectors() as $spamDetector) {
            if($spamDetector->detect($string)) {
                $failure++;
            }
        }

        return new SpamResult($failure > 0 ? true : false);
    }

    /**
     * Registers a Spam Detector
     *
     * @param SpamDetectorInterface $spamDetector
     *
     * @return SpamFilter
     */
    public function registerDetector(SpamDetectorInterface $spamDetector)
    {
        $detectorClass = get_class($spamDetector);
        $this->_spamDetectors[$detectorClass] = $spamDetector;

        return $this;
    }

    /**
     * Gets a list of all spam detectors
     *
     * @return SpamDetectorInterface[]
     */
    public function getDetectors()
    {
        return $this->_spamDetectors;
    }
}
