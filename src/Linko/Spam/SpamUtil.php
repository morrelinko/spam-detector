<?php namespace Linko\Spam;

class SpamUtil 
{
    /**
     * @param string $text
     *
     * @return mixed
     */
    public static function burstText($text)
    {
        // Convert some characters that 'MAY' be used as alias
        $text = str_replace(array("@", "$", "[dot]", "(dot)", "0"), array("at", "s", ".", ".", "o"), $text);

        // Remove special characters
        $text = preg_replace("/[^a-zA-Z0-9-\.]/", "", $text);

        // Strip multiple dots (.) to one. eg site......com to site.com
        $text = preg_replace("/\.{2,}/", ".", $text);

        return $text;
    }
}