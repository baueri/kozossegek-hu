<?php

declare(strict_types=1);

namespace Framework\Support;

final class StringHelper
{
    /**
     *
     * @param string $text
     * @param int $numberOfWords
     * @param string $moreText
     * @return string
     */
    public static function more(string $text, int $numberOfWords, string $moreText = ''): string
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

    public static function slugify($text, $divider = '-'): string
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

    public static function sanitize($buffer): string
    {
        $search = [
            '/>[^\S ]+/',     // strip whitespaces after tags, except space
            '/[^\S ]+</',     // strip whitespaces before tags, except space
            '/(\s)+/',         // shorten multiple whitespace sequences
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
        if (self::endsWith($word, 'y')) {
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

    public static function mask(string $text, int $keep = 3): string
    {
        return substr($text, 0, $keep) . str_repeat('*', mb_strlen($text) - $keep);
    }

    public static function maskEmail(string $email, int $keep = 3): string
    {
        if (!($at_pos = strpos($email, '@'))) {
            return '';
        }

        return self::mask(substr($email, 0, $at_pos), $keep) . substr($email, $at_pos);
    }
}
