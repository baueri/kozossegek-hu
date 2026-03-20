<?php

declare(strict_types=1);

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
        self::NOTIFICATION_TYPE_WARNING => Color::yellow,
    ];

    public static function color($text, $color): string
    {
        return "\033[{$color}m{$text}\033[0m";
    }

    public static function write_f(string $format, ...$params): void
    {
        static::write(sprintf($format, ...$params));
    }

    public static function write(string $text, Color $color = Color::default): void
    {
        print("\033[" . $color->value . "m" . $text . "\033[0m");
    }

    public static function heading(string $msg, Color $borderColor = Color::blue): void
    {
        static::writeln(str_repeat('-', strlen($msg) + 5), $borderColor);
        static::writeln($msg);
        static::writeln(str_repeat('-', strlen($msg) + 5), $borderColor);
        static::writeln();
    }

    public static function writeln(string $text = '', Color $color = Color::white): void
    {
        static::write("$text\r\n", $color);
    }

    public static function success(string $msg): void
    {
        static::notify(self::NOTIFICATION_TYPE_SUCCESS, $msg);
    }

    public static function notify(string $type, string $message, bool $nl = true): void
    {
        static::write("[" . $type . "] ", self::NOTIFICATION_COLORS[$type]);
        if ($nl) {
            static::writeln($message);
        } else {
            static::write($message);
        }
    }

    public static function info(string $msg, bool $nl = true): void
    {
        static::notify(self::NOTIFICATION_TYPE_INFO, $msg, $nl);
    }

    public static function fatal(string $msg): never
    {
        static::error($msg);
        static::writeln('A kód nem futott végig', Color::red);
        die();
    }

    public static function error(string $msg): void
    {
        static::notify(self::NOTIFICATION_TYPE_ERROR, $msg);
    }

    public static function warning(string $msg): void
    {
        static::notify(static::NOTIFICATION_TYPE_WARNING, $msg);
    }

    public static function dump($data): void
    {
        $color = is_bool($data) ? Color::cyan : Color::white;
        static::write(print_r($data, true), $color);
    }
}
