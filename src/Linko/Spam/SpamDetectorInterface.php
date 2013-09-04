<?php namespace Linko\Spam;

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