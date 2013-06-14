<?php namespace Linko\Spam\Detector;

use Linko\Spam\SpamDetectorInterface;

class BlackList implements SpamDetectorInterface
{
    /**
     * Holds blacklisted words
     *
     * @var array
     */
    private $_blackLists = array();

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        if (isset($options['blackLists'])) {
            $this->_blackLists = $options['blackLists'];
        }
    }

    /**
     * Adds a word/pattern to the black list.
     * Set the second argument to true to treat
     * the added word as a regular expression.
     *
     *
     * @param string $vars List of blacklisted words
     * @param bool $regex Flags word as regex pattern
     *
     * @return BlackList
     */
    public function add($vars, $regex = false)
    {
        if (!is_array($vars)) {
            $vars = array($vars);
        }

        foreach ($vars as $var) {
            $this->_blackLists[] = $regex ? '[' . $var . ']' : $var;
        }

        return $this;
    }

    /**
     * Defined in SpamDetectorInterface
     * Checks a string if it contains any word that is blacklisted.
     *
     * @param string $string
     *
     * @return bool
     */
    public function detect($string)
    {
        $blackListRegex = sprintf('~%s~', implode('|', array_map(function ($value) {
            if (isset($value[0]) && $value[0] == '[') {
                $value = substr($value, 1, -1);
            }
            else {
                $value = preg_quote($value);
            }

            return $value;
        }, $this->_blackLists)));

        return (bool)preg_match($blackListRegex, $string);
    }
}