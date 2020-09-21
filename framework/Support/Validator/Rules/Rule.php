<?php


namespace Framework\Support\Validator\Rules;


interface Rule
{
    /**
     * @return string
     */
    public static function getName();

    /**
     * @param $rule
     * @param string $field
     * @param $value
     * @param array $inputs
     * @param array $errors
     * @return bool
     */
    public function validate($rule, $field, $value, $inputs = [], &$errors = []);

}