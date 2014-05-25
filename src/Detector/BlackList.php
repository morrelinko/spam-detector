<?php namespace SpamDetector\Detector;

use SpamDetector\SpamDetectorInterface;
use SpamDetector\SpamUtil;

/**
 * Spam BlackLists Detector
 *
 * @author Morrison Laju <morrelinko@gmail.com>
 */
class BlackList implements SpamDetectorInterface
{
    /**
     * Holds blacklisted words
     *
     * @var array
     */
    private $_blackLists = array();

    /**
     * Holds the file that stores blacklisted words
     *
     * @var null
     */
    private $_listFile = null;

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

        if (isset($options['listFile'])) {
            $this->setListFile($options['listFile']);
        }
    }

    /**
     * Adds a word/pattern to the black list.
     * Set the second argument to true to treat
     * the added word as a regular expression.
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
     * Sets black list file
     *
     * @param string $file
     *
     * @throws \RuntimeException
     */
    public function setListFile($file)
    {
        if (!file_exists($file)) {
            throw new \RuntimeException(sprintf(
                "Could not find black list file [%s]",
                $file
            ));
        }

        $this->_listFile = $file;
    }

    /**
     * Checks the text if it contains any word that is blacklisted.
     *
     * @param array $data
     * @param array $options
     *
     * @return bool
     */
    public function detect($data, $options = array())
    {
        // We only need the text from the data
        $text = SpamUtil::burstText($data['text']);

        $fileList = array();

        if ($this->_listFile) {
            $fileList = array_map('trim',
                explode("\n", file_get_contents($this->_listFile))
            );
        }

        $blackLists = array_merge($this->_blackLists, $fileList);

        $blackListRegex = sprintf('!%s!', implode('|', array_map(function ($value) {
            if (isset($value[0]) && $value[0] == '[') {
                $value = substr($value, 1, -1);
            } else {
                $value = preg_quote($value);
            }

            return '(?:' . $value . ')';
        }, $blackLists)));

        return (bool)preg_match($blackListRegex, $text);
    }
}
