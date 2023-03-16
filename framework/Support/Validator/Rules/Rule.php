<?php

declare(strict_types=1);

namespace Framework\Support\Validator\Rules;

interface Rule
{
    public static function getName(): string;

    public function validate($rule, $field, $value, $inputs = [], &$errors = []): bool;
}
