<?php

namespace Framework\Console;

class Out
{
    /**
     * pre registered colors for console output
     */
    const COLOR_WHITE = '1;37';
    const COLOR_BLACK = '0;30';
    const COLOR_RED = '0;31';
    const COLOR_LIGHT_RED = '1;31';
    const COLOR_GREEN = '0;32';
    const COLOR_BLUE = '0;34';
    const COLOR_MAGENTA = '0;35';
    const COLOR_YELLOW = '0;33';
    const COLOR_CYAN = '0;36';
    const COLOR_GRAY = '1;30';
    const COLOR_LIGHT_GRAY = '0;37';

    /**
     * Notification types
     */
    const NOTIFICATION_TYPE_SUCCESS = 'SUCCESS';
    const NOTIFICATION_TYPE_INFO = 'INFO';
    const NOTIFICATION_TYPE_ERROR = 'ERROR';
    const NOTIFICATION_TYPE_WARNING = 'WARNING';

    /**
     * Notification color
     */
    const NOTIFICATION_COLORS = [
        self::NOTIFICATION_TYPE_SUCCESS => self::COLOR_GREEN,
        self::NOTIFICATION_TYPE_INFO => self::COLOR_BLUE,
        self::NOTIFICATION_TYPE_ERROR => self::COLOR_RED,
        self::NOTIFICATION_TYPE_WARNING => self::COLOR_MAGENTA,
    ];

    public static function color($text, $color)
    {
        return "\033[" . $color . "m" . $text . "\033[0m";

    }

    /**
     * Write to command line with formatted string
     *
     * @param string $format
     * @param mixed ...$params
     *
     */
    public static function write_f($format, ...$params)
    {
        static::write(sprintf($format, ...$params));
    }

    /**
     * Write a text to the command line
     *
     * @param string $text
     * @param string $color
     *
     */
    public static function write($text, $color = self::COLOR_WHITE)
    {
        print("\033[" . $color . "m" . $text . "\033[0m");
    }

    /**
     * @param string $msg
     * @param string $borderColor
     */
    public static function heading($msg, string $borderColor = self::COLOR_BLUE)
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
     *
     */
    public static function success($msg)
    {
        static::notify(self::NOTIFICATION_TYPE_SUCCESS, $msg);
    }

    /**
     * Prints a notification to command line
     *
     * @param string $type
     * @param string $message
     *
     */
    public static function notify($type, $message)
    {
        static::write("[" . $type . "] ", self::NOTIFICATION_COLORS[$type]);
        static::writeln($message . "\n");
    }

    /**
     * Info
     *
     * @param string $msg
     */
    public static function info($msg)
    {
    }

    /**
     * Error notification and instantly ends process
     * @param string $msg
     */
    public static function fatal($msg)
    {
        static::error($msg);
        static::writeln('A kód nem futott végig', self::COLOR_RED);
        die();
    }

    /**
     * Error notification
     *
     * @param string $msg
     *
     */
    public static function error($msg)
    {
        static::notify(self::NOTIFICATION_TYPE_ERROR, $msg);
    }

    /**
     * @param $msg
     */
    public static function warning($msg)
    {
        static::notify(static::NOTIFICATION_TYPE_WARNING, $msg);
    }

    /**
     * Dumps the data with print_r function
     *
     * @param string $data
     *
     */
    public static function dump($data)
    {
        $color = is_bool($data) ? static::COLOR_CYAN : null;
        static::write(print_r($data, true), $color);
    }
}
