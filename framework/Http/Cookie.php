<?php

namespace Framework\Http;

class Cookie
{
    const TEST_COOKIE_KEY = 'kozossegek_test_cookie';

    public static function enabled()
    {
        return isset($_COOKIE[self::TEST_COOKIE_KEY]);
    }

    public static function setTestCookie()
    {
        $_COOKIE[self::TEST_COOKIE_KEY] = 'kozossegek.hu Test Cookie';
    }
}
