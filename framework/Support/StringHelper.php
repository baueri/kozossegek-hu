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
    public static function more($text, int $numberOfWords, $moreText = ''): string
    {
        $text = strip_tags($text);
        if (str_word_count($text) > $numberOfWords) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = trim(substr($text, 0, $pos[$numberOfWords]), ' ') . $moreText;
        }

        return $text;
    }

    public static function shorten(?string $text, int $numberOfCharacters, string $moreText = ''): string
    {
        if (mb_strlen((string) $text) <= $numberOfCharacters) {
            return (string) $text;
        }

        return mb_substr($text, 0, $numberOfCharacters) . $moreText;
    }

    public static function camel($text): string
    {
        return lcfirst(str_replace(' ', '', ucwords(preg_replace('/[^a-zA-Z0-9\x7f-\xff]++/', ' ', $text))));
    }

    public static function snake($text, string $delimiter = '_'): string
    {
        return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], '\1' . $delimiter . '\2', ucfirst($text)));
    }

    public static function slugify($text, $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function convertSpecialChars($string): string
    {
        return preg_replace("/&([a-z])[a-z]+;/i", "$1", iconv('utf-8', 'us-ascii//TRANSLIT', $string));
    }

    public static function sanitize($buffer): string
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

    public static function isEmail($string): bool
    {
        return (bool) filter_var($string, FILTER_VALIDATE_EMAIL);
    }

    public static function wrap(string $string, string $before, ?string $after = null): string
    {
        $after = $after ?? $before;
        return "{$before}{$string}{$after}";
    }

    public static function plural($word): string
    {
        if (static::endsWith($word, 'y')) {
            return substr($word, 0, strlen($word) - 1) . 'ies';
        }

        return "{$word}s";
    }

    public static function endsWith($string, $endsWith): bool
    {
        return strrpos($string, $endsWith) === strlen($string) - strlen($endsWith);
    }

    public static function startsWith($string, $startsWith): bool
    {
        return str_starts_with($string, $startsWith);
    }
}
