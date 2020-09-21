<?php


namespace Framework\Support;


class Password
{
    /**
     * @var int
     */
    private static $algo = PASSWORD_BCRYPT;

    /**
     * @param $password
     * @return bool|string
     *
     */
    public static function hash($password)
    {
        return password_hash($password, static::$algo);
    }

    /**
     * @param $password
     * @param $hashedPassword
     * @return bool
     */
    public static function verify($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
}