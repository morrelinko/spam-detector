<?php namespace SpamDetector;

/**
 * @author Morrison Laju <morrelinko@gmail.com>
 */
interface SpamDetectorInterface
{
    /**
     * @param array $data
     * @param array $options
     *
     * @return mixed
     */
    public function detect($data, $options = array());
}