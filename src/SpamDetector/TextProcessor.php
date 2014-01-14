<?php namespace SpamDetector;

class TextProcessor
{
    public function prepare($text, array $options)
    {
        if (isset($options["asciiConversion"])) {
            setlocale(LC_ALL, 'en_us.UTF8');
            $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        }

        $text = trim(strtolower($text));
        $text = str_replace(array("\t", "\r\n", "\r", "\n"), "", $text);

        return $text;
    }
} 