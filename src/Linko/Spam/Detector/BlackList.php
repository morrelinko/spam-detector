<?php namespace Linko\Spam\Detector;

use Linko\Spam\SpamDetectorInterface;

class BlackList implements SpamDetectorInterface
{
    private $_blackLists = array();

    public function __construct(array $options = array())
    {
        if (isset($options['blackLists'])) {
            $this->_blackLists = $options['blackLists'];
        }
    }

    /**
     * @param $vars
     * @param bool $regex
     */
    public function add($vars, $regex = false)
    {
        if (!is_array($vars)) {
            $vars = array($vars);
        }

        foreach ($vars as $var) {
            $this->_blackLists[] = $regex ? '[' . $var . ']' : $var;
        }
    }

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

        return preg_match($blackListRegex, $string);
    }
}