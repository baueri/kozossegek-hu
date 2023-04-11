<?php

namespace Framework\Http;

class Response
{
    public static function asJson()
    {
        header('Content-Type: application/json');
    }

    public static function headers()
    {
        $headers = headers_list();
        foreach ($headers as $i => $row) {
            [$key, $value] = explode(': ', $row);
            unset($headers[$i]);
            $headers[$key] = $value;
        }

        return $headers;
    }

    public static function setStatusCode($code)
    {
        http_response_code((int) $code);
    }

    public static function getHeader($name)
    {
        return static::headers()[$name] ?? null;
    }

    public static function contentTypeIsJson()
    {
        return static::getHeader('Content-Type') == 'application/json';
    }
}
