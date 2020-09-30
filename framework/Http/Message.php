<?php

namespace Framework\Http;

class Message
{

    const MSG_SUCCESS = 'success';
    const MSG_WARNING = 'warning';
    const MSG_INFO    = 'info';
    const MSG_DANGER  = 'danger';
    
    const SESSION_KEY_NAME = 'system_message';
    
    public static function set($message, $type, $list = null)
    {
        $_SESSION[self::SESSION_KEY_NAME] = [
            'message' => $message,
            'type' => $type,
            'list' => $list
        ];
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
    public static function get()
    {
        $data = static::getSessionData();
        static::destroySessionData();
        
        return $data;
    }
    
    /**
     * @return array|NULL
     */
    public static function flash()
    {
        return static::get();
    }
    
    /**
     * @return array|null
     */
    private static function getSessionData()
    {
        return $_SESSION[self::SESSION_KEY_NAME];
    }
    
    private function destroySessionData()
    {
        unset($_SESSION[self::SESSION_KEY_NAME]);
    }

}
