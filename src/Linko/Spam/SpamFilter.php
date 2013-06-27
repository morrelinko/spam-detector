<?php namespace Linko\Spam;

class SpamFilter
{
    /**
     * Holds registered spam detectors
     *
     * @var SpamDetectorInterface[]
     */
    protected $_spamDetectors = array();

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
        $string = $this->_normalizeString($string);

        foreach ($this->_spamDetectors as $spamDetector) {
            if($spamDetector->detect($string)) {
                $failure++;
            }
        }

        return new SpamResult($failure > 0);
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

    /**
     * Used to normalize string before passing
     * it to detectors
     *
     * @param string $string
     *
     * @return string
     */
    private function _normalizeString($string)
    {
        return trim(strtolower($string));
    }
}
