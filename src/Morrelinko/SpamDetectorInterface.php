<?php namespace Linko\Spam;

interface SpamDetectorInterface
{
    public function detect($string);
}