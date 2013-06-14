<?php namespace Linko\Spam;

interface SpamDetectorInterface
{
    /**
     * @param string $string
     *
     * @return mixed
     */
    public function detect($string);
}