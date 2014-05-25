<?php

namespace SpamDetector\Detector;

/**
 * Spam BlackLists Detector
 *
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class BlackList implements SpamDetectorInterface
{
    /**
     * @var string
     */
    protected $regex;

    /**
     * @var bool
     */
    protected $rebuild = false;

    /**
     * Holds blacklisted words
     *
     * @var array
     */
    protected $blackLists = array();

    /**
     * Holds the file that stores blacklisted words
     *
     * @var null
     */
    protected $listFile = null;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        if (isset($options['blackLists'])) {
            $this->blackLists = $options['blackLists'];
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
     * @return BlackList
     */
    public function add($vars, $regex = false)
    {
        if (!is_array($vars)) {
            $vars = array($vars);
        }

        foreach ($vars as $var) {
            $this->blackLists[] = $regex ? '[' . $var . ']' : $var;
        }

        return $this;
    }

    /**
     * Sets black list file
     *
     * @param string $file
     * @throws \RuntimeException
     */
    public function setListFile($file)
    {
        if (!file_exists($file)) {
            throw new \RuntimeException(sprintf(
                "Could not find blacklist file [%s]",
                $file
            ));
        }

        $this->listFile = $file;
    }

    /**
     * @param $flag
     */
    protected function rebuildRegex($flag)
    {
        $this->rebuild = $flag;
    }

    /**
     * Checks the text if it contains any word that is blacklisted.
     *
     * @param array $data
     * @return bool
     */
    public function detect($data)
    {
        // We only need the text from the data
        $text = $data['text'];

        if (!$this->regex || $this->rebuild) {
            $fileList = array();
            if ($this->listFile) {
                $fileList = array_map('trim', explode("\n", file_get_contents($this->listFile)));
            }

            $blackLists = array_merge($this->blackLists, $fileList);

            $this->regex = sprintf('~%s~', implode('|', array_map(function ($value) {
                if (isset($value[0]) && $value[0] == '[') {
                    $value = substr($value, 1, -1);
                } else {
                    $value = preg_quote($value);
                }

                return '(?:' . $value . ')';
            }, $blackLists)));
        }

        return (bool) preg_match($this->regex, $text);
    }
}
