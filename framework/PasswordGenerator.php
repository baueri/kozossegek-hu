<?php


namespace Framework;


use InvalidArgumentException;

class PasswordGenerator
{
    const LETTERS = 'abcdefghijklmnopqrstuvwxyz';
    const NUMBERS = '0123456789';

    const OPTION_LOWER = 'LOWER';
    const OPTION_UPPER = 'UPPER';
    const OPTION_NUMBERS = 'NUMBER';

    private $settings = [
        self::OPTION_LOWER => true,
        self::OPTION_UPPER => true,
        self::OPTION_NUMBERS => true,
    ];

    private $length;

    /**
     * PasswordGenerator constructor.
     * @param int $length
     */
    public function __construct($length = 8)
    {
        $this->length = $length;
    }

    public function generate()
    {
        $pattern = $this->settings[self::OPTION_LOWER] ? self::LETTERS : '';
        if ($this->settings[self::OPTION_UPPER]) {
            $pattern .= strtoupper(static::LETTERS);
        }
        if ($this->settings[self::OPTION_NUMBERS]) {
            $pattern .= self::NUMBERS;
        }

        $pwd = '';
        $patternArr = str_split($pattern);
        for ($i = 0; $i < $this->length; $i++) {
            $pwd .= $patternArr[array_rand($patternArr)];
        }
        return $pwd;
    }

    public function setOpt($option, $value)
    {
        if (!isset($this->settings[$option])) {
            throw new InvalidArgumentException('Invalid password option: ' . $option);
        }
        if (!is_bool($value)) {
            throw new InvalidArgumentException('Invalid password option value');
        }

        $this->settings[$option] = $value;
        return $this;
    }

    public function __toString()
    {
        return $this->generate();
    }
}
