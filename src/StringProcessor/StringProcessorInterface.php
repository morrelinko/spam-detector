<?php

namespace SpamDetector\StringProcessor;

/**
 * Interface StringProcessorInterface
 *
 * @author Laju Morrison <morrelinko@gmail.com>
 */
interface StringProcessorInterface
{
    /**
     * @param $string
     * @return mixed
     */
    public function prepare($string);
}
