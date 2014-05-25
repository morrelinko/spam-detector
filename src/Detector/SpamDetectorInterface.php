<?php

namespace SpamDetector\Detector;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
interface SpamDetectorInterface
{
    /**
     * @param array $data
     * @param array $options
     * @return bool
     */
    public function detect($data, $options = array());
}