<?php namespace Linko\Spam;

interface SpamDetectorInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function detect($data);
}