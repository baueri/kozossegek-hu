<?php


namespace Framework\Support\Validator\Rules;


class RequiredRule implements Rule
{

    /**
     * @return string
     */
    public static function getName()
    {
        return 'required';
    }

    /**
     * @inheritDoc
     */
    public function validate($rule, $field, $value, $inputs = [], &$errors = [])
    {
        if (!$value) {
            $errors["required"] = lang('errors.field_is_required');
            return false;
        }

        return true;
    }
}
