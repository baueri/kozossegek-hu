<?php

namespace Framework\Http;

class Cookie
{
    public const TEST_COOKIE_KEY = 'kozossegek_test_cookie';

    public static function enabled(): bool
    {
        return isset($_COOKIE[self::TEST_COOKIE_KEY]);
    }

    public static function setTestCookie(): void
    {
        $_COOKIE[self::TEST_COOKIE_KEY] = 'kozossegek.hu Test Cookie';
    }
}
