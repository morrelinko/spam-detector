<?php namespace SpamDetector;

/**
 * @author Morrison Laju <morrelinko@gmail.com>
 */
class SpamResult
{
    /**
     * @var bool
     */
    private $_isSpam = false;

    /**
     * @var array
     */
    private $_messages = array();

    /**
     * Constructor
     *
     * @param bool $isSpam
     * @param array $messages
     */
    public function __construct($isSpam, array $messages = array())
    {
        $this->_isSpam = $isSpam;
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
        return $this->_isSpam == false;
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
        return $this->_messages;
    }
}
