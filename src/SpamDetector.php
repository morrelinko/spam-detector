<?php namespace SpamDetector;

/**
 * @author Morrison Laju <morrelinko@gmail.com>
 */
class SpamDetector
{
    /**
     * Holds registered spam detectors
     *
     * @var SpamDetectorInterface[]
     */
    protected $detectors = array();

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var TextProcessor
     */
    protected $textProcessor;

    /**
     * @param TextProcessor $textProcessor
     */
    public function setTextProcessor(TextProcessor $textProcessor)
    {
        $this->textProcessor = $textProcessor;
    }

    /**
     * Checks if a string is spam or not
     *
     * @param string|array $data
     * @param array $options
     *
     * @return SpamResult
     */
    public function check($data, $options = array())
    {
        $options = $this->optionsFor($options);

        $failure = 0;
        if (is_string($data)) {
            $data = array("text" => $data);
        }

        $data = $this->prepare($data, $options);

        foreach ($this->detectors as $detector) {
            if ($detector->detect($data, $options)) {
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
     * @return SpamDetector
     */
    public function registerDetector(SpamDetectorInterface $spamDetector)
    {
        $detectorId = $this->classSimpleName($spamDetector);

        if (isset($this->detectors[$detectorId])) {
            throw new \RuntimeException(
                "Spam Detector [%s] already exists", $detectorId);
        }

        $this->detectors[$detectorId] = $spamDetector;

        return $this;
    }

    /**
     * Gets a detector using its detector ID (Class Simple Name)
     *
     * @param string $detectorId
     *
     * @return bool|\SpamDetector\SpamDetectorInterface
     */
    public function getDetector($detectorId)
    {
        if (!isset($this->detectors[$detectorId])) {
            return false;
        }

        return $this->detectors[$detectorId];
    }

    /**
     * Gets a list of all spam detectors
     *
     * @return SpamDetectorInterface[]
     */
    public function getDetectors()
    {
        return $this->detectors;
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function optionsFor(array $options)
    {
        return array_merge(array(
            "asciiConversion" => true
        ), $options);
    }

    /**
     * Used to normalize string before passing
     * it to detectors
     *
     * @param array $data
     * @param $options
     *
     * @return string
     */
    protected function prepare(array $data, $options = array())
    {
        if ($this->textProcessor == null) {
            $this->textProcessor = new TextProcessor();
        }

        $data = array_merge(array(
            "ip" => $this->getIp(),
            "userAgent" => $this->getUserAgent(),
            "name" => null,
            "email" => null,
            "text" => null
        ), $data);

        $data["original_text"] = $data["text"];
        $data["text"] = $this->textProcessor->prepare($data["text"], $options);

        return $data;
    }

    /**
     * @return string|null
     */
    protected function getIp()
    {
        return isset($_SERVER['REMOTE_ADDR'])
            ? $_SERVER['REMOTE_ADDR']
            : null;
    }

    /**
     * @return string|null
     */
    protected function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT'])
            ? $_SERVER['HTTP_USER_AGENT']
            : null;
    }

    /**
     * Gets the name of a class (w. Namespaces removed)
     *
     * @param $class
     *
     * @return string
     */
    protected function classSimpleName($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        return substr($class, strrpos($class, '\\') + 1);
    }
}
