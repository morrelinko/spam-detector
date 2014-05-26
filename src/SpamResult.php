<?php

namespace SpamDetector;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class SpamResult
{
    /**
     * @var bool
     */
    protected $isSpam = false;

    /**
     * @var array
     */
    protected $messages = array();

    /**
     * Constructor
     *
     * @param bool $isSpam
     * @param array $messages
     */
    public function __construct($isSpam, array $messages = array())
    {
        $this->isSpam = $isSpam;
    }

    /**
     * Alias of SpamResult::failed();
     *
     * @return bool
     */
    public function isSpam()
    {
        return $this->failed();
    }

    /**
     * @return bool
     */
    public function passed()
    {
        return $this->isSpam == false;
    }

    /**
     * @return bool
     */
    public function failed()
    {
        return !$this->passed();
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
