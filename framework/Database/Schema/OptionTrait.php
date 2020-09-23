<?php

namespace Framework\Database\Schema;

trait OptionTrait
{
    /**
     * @var array
     */
    protected array $options = [];

    /**
     * @param $key
     * @param null $default
     * @return array
     */
    public function getOption($key, $default = null)
    {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasOption($key)
    {
        return isset($this->options[$key]);
    }
}