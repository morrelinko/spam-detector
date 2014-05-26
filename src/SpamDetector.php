<?php

namespace SpamDetector;

use SpamDetector\Detector\SpamDetectorInterface;
use SpamDetector\StringProcessor\StringProcessorInterface;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class SpamDetector
{
    /**
     * Holds registered spam detectors
     *
     * @var Detector\SpamDetectorInterface[]
     */
    protected $detectors = array();

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var StringProcessorInterface
     */
    protected $stringProcessor;

    /**
     * @param StringProcessorInterface $stringProcessor
     */
    public function setStringProcessor(StringProcessorInterface $stringProcessor)
    {
        $this->stringProcessor = $stringProcessor;
    }

    /**
     * Checks if a string is spam or not
     *
     * @param string|array $data
     * @return SpamResult
     */
    public function check($data)
    {
        $failure = 0;
        if (is_string($data)) {
            $data = array('text' => $data);
        }

        $data = $this->prepareData($data);

        foreach ($this->detectors as $detector) {
            if ($detector->detect($data)) {
                $failure++;
            }
        }

        return new SpamResult($failure > 0);
    }

    /**
     * Registers a Spam Detector
     *
     * @param SpamDetectorInterface $spamDetector
     * @throws \RuntimeException
     * @return SpamDetector
     */
    public function registerDetector(SpamDetectorInterface $spamDetector)
    {
        $detectorId = $this->classSimpleName($spamDetector);

        if (isset($this->detectors[$detectorId])) {
            throw new \RuntimeException(
                'Spam Detector [%s] already registered',
                $detectorId
            );
        }

        $this->detectors[$detectorId] = $spamDetector;

        return $this;
    }

    /**
     * Gets a detector using its detector ID (Class Simple Name)
     *
     * @param string $detectorId
     * @return bool|SpamDetectorInterface
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
     * Used to normalize string before passing
     * it to detectors
     *
     * @param array $data
     * @return string
     */
    protected function prepareData(array $data)
    {
        $data = array_merge(array(
            'name' => null,
            'email' => null,
            'text' => null,
            'ip' => $this->getIp(),
            'user_agent' => $this->getUserAgent()
        ), $data);

        $data['original_text'] = $data['text'];
        $data['text'] = $this->stringProcessor ? $this->stringProcessor->prepare($data['text']) : $data['text'];

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
