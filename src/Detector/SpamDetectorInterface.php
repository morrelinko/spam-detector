<?php

namespace SpamDetector\Detector;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
interface SpamDetectorInterface
{
    /**
     * @param array $data
     * @return bool
     */
    public function detect($data);
}
