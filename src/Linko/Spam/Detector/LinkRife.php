<?php namespace Linko\Spam\Detector;

use Linko\Spam\SpamDetectorInterface;

/**
 * LinkRife : Link Overflow Detector
 *
 * Spam Detector that detects if a string contains
 * too many links.
 *
 * @author Morrison Laju <morrelinko@gmail.com>
 */
class LinkRife implements SpamDetectorInterface
{
    const URL_REGEX = "!((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)!";

    /**
     * @var int
     */
    private $_maxLinkAllowed = 10;

    /**
     * Ratio (In Percentage) of the number of links
     * to the number of words in the string. If the
     * percentage ratio is greater than the specified
     * ratio, it is considered a "Link Overflow"
     *
     * @var int
     *   Default 40%
     */
    private $_maxRatio = 40;

    /**
     * Sets the maximum number of links allowed in a text
     * before it is considered spam.
     *
     * @param int $count
     */
    public function setMaxLinkAllowed($count)
    {
        $this->_maxLinkAllowed = $count;
    }

    /**
     * @return int
     */
    public function getMaxLinkAllowed()
    {
        return $this->_maxLinkAllowed;
    }

    /**
     * @param int $ratio
     */
    public function setMaxRatio($ratio)
    {
        $this->_maxRatio = $ratio;
    }

    /**
     * @return int
     */
    public function getMaxRatio()
    {
        return $this->_maxRatio;
    }

    /**
     * {@inheritDocs}
     */
    public function detect($data)
    {
        // We only need the text
        $text = $data['text'];

        $wordCount = str_word_count($text);
        preg_match_all(self::URL_REGEX, $text, $matches);
        $linkCount = count($matches[0]);

        if($linkCount >= $this->getMaxLinkAllowed()) {
            // If the link count is more than the maximum allowed
            // the string is automatically considered spam..
            return true;
        }

        // Get the ratio of words to link
        $ratio = floor(($linkCount / $wordCount) * 100);

        return $ratio >= $this->getMaxRatio();
    }
}