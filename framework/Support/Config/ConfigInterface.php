<?php


namespace Framework\Support\Config;


interface ConfigInterface
{
    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null);
}