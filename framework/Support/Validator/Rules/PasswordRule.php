<?php


namespace Framework\Support\Validator\Rules;


class PasswordRule implements Rule
{

    /**
     * @inheritDoc
     */
    public static function getName()
    {
        return 'password';
    }

    /**
     * @inheritDoc
     */
    public function validate($rule, $field, $value, $inputs = [], &$errors = [])
    {
        if (!$value) {
            $errors["password_missing"] = lang('errors.field_is_required');
        }

        if (!isset($inputs['password_again']) || $inputs['password_again'] && $value != $inputs['password_again']) {
            $errors["password_mismatch"] = lang('errors.password_mismatch');
            return false;
        }

        return true;
    }
}