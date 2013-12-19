<?php namespace Linko\Spam;

/**
 * @author Morrison Laju <morrelinko@gmail.com>
 */
class SpamFilter
{
    /**
     * Holds registered spam detectors
     *
     * @var SpamDetectorInterface[]
     */
    protected $spamDetectors = array();
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

        foreach ($this->spamDetectors as $spamDetector) {
            if ($spamDetector->detect($data, $options)) {
                $failure++;
            }
        }

        return new SpamResult($failure > 0);
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
        $data = array_merge(array(
            "ip" => $this->getIp(),
            "userAgent" => $this->getUserAgent(),
            "name" => null,
            "email" => null,
            "text" => null
        ), $data);

        if ($this->textProcessor == null) {
            $this->textProcessor = new TextProcessor();
        }

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
     * Registers a Spam Detector
     *
     * @param SpamDetectorInterface $spamDetector
     *
     * @throws \RuntimeException
     * @return SpamFilter
     */
    public function registerDetector(SpamDetectorInterface $spamDetector)
    {
        $detectorId = $this->classSimpleName($spamDetector);

        if (isset($this->spamDetectors[$detectorId])) {
            throw new \RuntimeException(
                "Spam Detector [%s] already exists", $detectorId);
        }

        $this->spamDetectors[$detectorId] = $spamDetector;

        return $this;
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

    /**
     * Gets a detector using its detector ID (Class Simple Name)
     *
     * @param string $detectorId
     *
     * @return bool|\Linko\Spam\SpamDetectorInterface
     */
    public function getDetector($detectorId)
    {
        if (!isset($this->spamDetectors[$detectorId])) {
            return false;
        }

        return $this->spamDetectors[$detectorId];
    }

    /**
     * Gets a list of all spam detectors
     *
     * @return SpamDetectorInterface[]
     */
    public function getDetectors()
    {
        return $this->spamDetectors;
    }
}
