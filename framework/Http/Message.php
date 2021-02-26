<?php

namespace Framework\Http;

class Message
{

    public const MSG_SUCCESS = 'success';
    public const MSG_WARNING = 'warning';
    public const MSG_INFO    = 'info';
    public const MSG_DANGER  = 'danger';

    public const SESSION_KEY_NAME = 'system_message';

    public static function set($message, $type, $list = null)
    {
        Session::set(self::SESSION_KEY_NAME, [
            'message' => $message,
            'type' => $type,
            'list' => $list
        ]);
    }

    public static function success($message, $list = null)
    {
        self::set($message, self::MSG_SUCCESS, $list);
    }

    public static function danger($message, $list = null)
    {
        self::set($message, self::MSG_DANGER, $list);
    }

    public static function warning($message, $list = null)
    {
        self::set($message, self::MSG_WARNING, $list);
    }

    public static function info($message, $list = null)
    {
        self::set($message, self::MSG_INFO, $list);
    }

    /**
     *
     * @return array|null
     */
    public static function flash()
    {
        return Session::flash(self::SESSION_KEY_NAME);
    }

}

