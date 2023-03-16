<?php

declare(strict_types=1);

namespace Framework\Support\Validator\Rules;

class RequiredRule implements Rule
{
    public static function getName(): string
    {
        return 'required';
    }

    public function validate($rule, $field, $value, $inputs = [], &$errors = []): bool
    {
        if (!$value) {
            $errors["required"] = lang('errors.field_is_required');
            return false;
        }

        return true;
    }
}
