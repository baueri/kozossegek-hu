<?php

declare(strict_types=1);

namespace Framework\Support\Validator\Rules;

class PasswordRule implements Rule
{
    public static function getName(): string
    {
        return 'password';
    }

    public function validate($rule, $field, $value, $inputs = [], &$errors = []): bool
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
