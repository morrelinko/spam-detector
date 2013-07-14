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
     * @param string|array $data
     *
     * @return SpamResult
     */
    public function check($data)
    {
        $failure = 0;
        if (is_string($data)) {
            $data = array('text' => $data);
        }

        $data = $this->prepare($data);

        foreach ($this->_spamDetectors as $spamDetector) {
            if ($spamDetector->detect($data)) {
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
     * @throws \RuntimeException
     * @return SpamFilter
     */
    public function registerDetector(SpamDetectorInterface $spamDetector)
    {
        $detectorId = $this->_classSimpleName($spamDetector);

        if (isset($this->_spamDetectors[$detectorId])) {
            throw new \RuntimeException(
                "Spam Detector [%s] already exists", $detectorId);
        }

        $this->_spamDetectors[$detectorId] = $spamDetector;

        return $this;
    }

    /**
     * Gets a detector using its detector ID (Class Simple Name)
     *
     * @param string $detectorId
     *
     * @return bool|\Linko\Spam\SpamDetectorInterface
     */
    public function getDetector($detectorId)
    {
        if (!isset($this->_spamDetectors[$detectorId])) {
            return false;
        }

        return $this->_spamDetectors[$detectorId];
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
     * @param array $data
     *
     * @return string
     */
    protected function prepare(array $data)
    {
        $data = array_merge(array(
            'name'      => null,
            'ip'        => $this->getIp(),
            'userAgent' => $this->getUserAgent(),
            'text'      => null
        ), $data);

        $data['text'] = trim(strtolower($data['text']));

        return $data;
    }

    protected function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    protected function getIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    /**
     * Gets the name of a class (w. Namespaces removed)
     *
     * @param $class
     *
     * @return string
     */
    private function _classSimpleName($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        return substr($class, strrpos($class, '\\') + 1);
    }
}
