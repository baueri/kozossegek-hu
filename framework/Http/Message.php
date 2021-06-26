<?php

namespace Framework\Http;

class Message
{
    public const MSG_SUCCESS = 'success';
    public const MSG_WARNING = 'warning';
    public const MSG_INFO    = 'info';
    public const MSG_DANGER  = 'danger';

    public const SESSION_KEY_NAME = 'system_message';

    public static function set(string $message, string $type, ?array $list = null): void
    {
        Session::set(self::SESSION_KEY_NAME, [
            'message' => $message,
            'type' => $type,
            'list' => $list
        ]);
    }

    public static function success(string $message, ?array $list = null): void
    {
        self::set($message, self::MSG_SUCCESS, $list);
    }

    public static function danger(string $message, ?array $list = null): void
    {
        self::set($message, self::MSG_DANGER, $list);
    }

    public static function warning(string $message, ?array $list = null): void
    {
        self::set($message, self::MSG_WARNING, $list);
    }

    public static function info(string $message, ?array $list = null): void
    {
        self::set($message, self::MSG_INFO, $list);
    }

    public static function flash(): ?array
    {
        return Session::flash(self::SESSION_KEY_NAME);
    }
}
