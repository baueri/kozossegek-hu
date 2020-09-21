<?php

namespace Framework\Support;

/**
 * Description of StringHelper
 *
 * @author ivan
 */
class StringHelper {
    
    public static function more($text, $numberOfWords, $moreText = '')
    {
        if (str_word_count($text, 0) > $numberOfWords) {
            $words = str_word_count($text, 2);
            $pos   = array_keys($words);
            $text  = trim(substr($text, 0, $pos[$numberOfWords]), ' ') . $moreText;
        }
        
        return $text;
    }
    
    public static function camel($text)
    {
        return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[^a-zA-Z0-9\x7f-\xff]++/', ' ', $text))));
    }
    
    public static function snake($text, $delimiter = '_')
    {
        return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], '\1' . $delimiter . '\2', ucfirst($text)));
    }
    
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
          return 'n-a';
        }

        return $text;
    }
}
