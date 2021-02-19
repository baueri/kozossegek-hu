<?php

namespace Framework\Support;

/**
 * Description of StringHelper
 *
 * @author ivan
 */
class StringHelper
{

    /**
     *
     * @param string $text
     * @param int $numberOfWords
     * @param string $moreText
     * @return string
     */
    public static function more($text, $numberOfWords, $moreText = '')
    {
        $text = strip_tags($text);
        if (str_word_count($text, 0) > $numberOfWords) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = trim(substr($text, 0, $pos[$numberOfWords]), ' ') . $moreText;
        }

        return $text;
    }

    /**
     * @param string|null $text
     * @param int $numberOfCharacters
     * @param string $moreText
     * @return string
     */
    public static function shorten(?string $text, int $numberOfCharacters, $moreText = ''): string
    {
        if (mb_strlen($text) <= $numberOfCharacters) {
            return (string) $text;
        }

        return mb_substr($text, 0, $numberOfCharacters) . $moreText;
    }

    /**
     * @param $text
     * @return string
     */
    public static function camel($text)
    {
        return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[^a-zA-Z0-9\x7f-\xff]++/', ' ', $text))));
    }

    /**
     * @param $text
     * @param string $delimiter
     * @return string
     */
    public static function snake($text, $delimiter = '_')
    {
        return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], '\1' . $delimiter . '\2', ucfirst($text)));
    }

    /**
     * @param $text
     * @return string
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities($text));

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

    /**
     *
     * @param string $string
     * @return string
     */
    public static function convertSpecialChars($string)
    {
        return preg_replace("/&([a-z])[a-z]+;/i", "$1", iconv('utf-8', 'us-ascii//TRANSLIT', $string));
    }

    /**
     * @param $buffer
     * @return string|string[]|null
     */
    public static function sanitize($buffer)
    {
        $search = [
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        ];

        $replace = ['>', '<', '\\1', ''];

        return preg_replace($search, $replace, $buffer);
    }

    /**
     * @param $string
     * @return mixed
     */
    public static function isEmail($string)
    {
        return (bool) filter_var($string, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param string $charList
     * @param int $numberOfCharactersToTake
     * @return string
     */
    public static function generateRandomStringFrom(string $charList, $numberOfCharactersToTake = 1): string
    {
        $text = '';

        for ($i = 0; $i < $numberOfCharactersToTake; $i++) {
            $text .= DataSet::random(mb_str_split($charList));
        }

        return $text;
    }

}
