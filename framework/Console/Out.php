<?php

namespace Framework\Console;

class Out
{
    public const NOTIFICATION_TYPE_SUCCESS = 'SUCCESS';
    public const NOTIFICATION_TYPE_INFO = 'INFO';
    public const NOTIFICATION_TYPE_ERROR = 'ERROR';
    public const NOTIFICATION_TYPE_WARNING = 'WARNING';

    public const NOTIFICATION_COLORS = [
        self::NOTIFICATION_TYPE_SUCCESS => Color::green,
        self::NOTIFICATION_TYPE_INFO => Color::blue,
        self::NOTIFICATION_TYPE_ERROR => Color::red,
        self::NOTIFICATION_TYPE_WARNING => Color::magenta,
    ];

    public static function color($text, $color): string
    {
        return "\033[{$color}m{$text}\033[0m";
    }

    public static function write_f(string $format, ...$params)
    {
        static::write(sprintf($format, ...$params));
    }

    public static function write(string $text, Color $color = Color::white)
    {
        print("\033[" . $color->value . "m" . $text . "\033[0m");
    }

    public static function heading(string $msg, Color $borderColor = Color::blue)
    {
        static::writeln(str_repeat('-', strlen($msg) + 5), $borderColor);
        static::writeln($msg);
        static::writeln(str_repeat('-', strlen($msg) + 5), $borderColor);
        static::writeln();
    }

    public static function writeln(string $text = '', Color $color = Color::white)
    {
        static::write("$text\r\n", $color);
    }

    public static function success(string $msg)
    {
        static::notify(self::NOTIFICATION_TYPE_SUCCESS, $msg);
    }

    public static function notify(string $type, string $message)
    {
        static::write("[" . $type . "] ", self::NOTIFICATION_COLORS[$type]);
        static::writeln($message . "\n");
    }

    public static function info(string $msg)
    {
        static::notify(self::NOTIFICATION_TYPE_INFO, $msg);
    }

    public static function fatal(string $msg): never
    {
        static::error($msg);
        static::writeln('A kód nem futott végig', Color::red);
        die();
    }

    public static function error(string $msg)
    {
        static::notify(self::NOTIFICATION_TYPE_ERROR, $msg);
    }

    public static function warning(string $msg)
    {
        static::notify(static::NOTIFICATION_TYPE_WARNING, $msg);
    }

    public static function dump($data)
    {
        $color = is_bool($data) ? Color::cyan : null;
        static::write(print_r($data, true), $color);
    }
}
