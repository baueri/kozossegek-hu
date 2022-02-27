<?php

namespace Framework\Console;

use JetBrains\PhpStorm\NoReturn;

class Out
{
    /**
     * pre-registered colors for console output
     */
    public const COLOR_WHITE = '1;37';
    public const COLOR_BLACK = '0;30';
    public const COLOR_RED = '0;31';
    public const COLOR_LIGHT_RED = '1;31';
    public const COLOR_GREEN = '0;32';
    public const COLOR_BLUE = '0;34';
    public const COLOR_MAGENTA = '0;35';
    public const COLOR_YELLOW = '0;33';
    public const COLOR_CYAN = '0;36';
    public const COLOR_GRAY = '1;30';
    public const COLOR_LIGHT_GRAY = '0;37';

    /**
     * Notification types
     */
    public const NOTIFICATION_TYPE_SUCCESS = 'SUCCESS';
    public const NOTIFICATION_TYPE_INFO = 'INFO';
    public const NOTIFICATION_TYPE_ERROR = 'ERROR';
    public const NOTIFICATION_TYPE_WARNING = 'WARNING';

    /**
     * Notification color
     */
    public const NOTIFICATION_COLORS = [
        self::NOTIFICATION_TYPE_SUCCESS => self::COLOR_GREEN,
        self::NOTIFICATION_TYPE_INFO => self::COLOR_BLUE,
        self::NOTIFICATION_TYPE_ERROR => self::COLOR_RED,
        self::NOTIFICATION_TYPE_WARNING => self::COLOR_MAGENTA,
    ];

    public static function color($text, $color): string
    {
        return "\033[{$color}m{$text}\033[0m";
    }

    /**
     * Write to command line with formatted string
     *
     * @param string $format
     * @param mixed ...$params
     */
    public static function write_f(string $format, ...$params)
    {
        static::write(sprintf($format, ...$params));
    }

    /**
     * Write a text to the command line
     *
     * @param string $text
     * @param string $color
     */
    public static function write(string $text, string $color = self::COLOR_WHITE)
    {
        print("\033[" . $color . "m" . $text . "\033[0m");
    }

    /**
     * @param string $msg
     * @param string $borderColor
     */
    public static function heading(string $msg, string $borderColor = self::COLOR_BLUE)
    {
        static::writeln(str_repeat('-', strlen($msg) + 5), $borderColor);
        static::writeln($msg);
        static::writeln(str_repeat('-', strlen($msg) + 5), $borderColor);
        static::writeln();
    }

    /**
     * Write text to command line with EOL
     *
     * @param string $text
     * @param string $color
     *
     */
    public static function writeln($text = '', $color = self::COLOR_WHITE)
    {
        static::write("$text\r\n", $color);
    }

    /**
     * Success notification
     *
     * @param string $msg
     */
    public static function success(string $msg)
    {
        static::notify(self::NOTIFICATION_TYPE_SUCCESS, $msg);
    }

    /**
     * Prints a notification to command line
     *
     * @param string $type
     * @param string $message
     */
    public static function notify(string $type, string $message)
    {
        static::write("[" . $type . "] ", self::NOTIFICATION_COLORS[$type]);
        static::writeln($message . "\n");
    }

    public static function info(string $msg)
    {
        static::notify(self::NOTIFICATION_TYPE_INFO, $msg);
    }

    /**
     * Error notification and instantly ends process
     */
    public static function fatal(string $msg): never
    {
        static::error($msg);
        static::writeln('A kód nem futott végig', self::COLOR_RED);
        die();
    }

    /**
     * Error notification
     *
     * @param string $msg
     */
    public static function error(string $msg)
    {
        static::notify(self::NOTIFICATION_TYPE_ERROR, $msg);
    }

    /**
     * @param string $msg
     */
    public static function warning(string $msg)
    {
        static::notify(static::NOTIFICATION_TYPE_WARNING, $msg);
    }

    public static function dump($data)
    {
        $color = is_bool($data) ? static::COLOR_CYAN : null;
        static::write(print_r($data, true), $color);
    }
}
