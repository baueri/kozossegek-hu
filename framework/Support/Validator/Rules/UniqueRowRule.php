<?php

declare(strict_types=1);

namespace Framework\Support\Validator\Rules;

use Framework\Database\Database;

class UniqueRowRule implements Rule
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public static function getName(): string
    {
        return 'unique';
    }

    public function validate($rule, $field, $value, $inputs = [], &$errors = []): bool
    {
        if (!$value) {
            return false;
        }

        [, $table] = explode(':', $rule, 2);

        $column = $field;

        if (str_contains($table, '.')) {
            [$table, $column] = explode('.', $table);
        }

        $exists = (bool) $this->database->first("SELECT 1 as `exists` FROM $table WHERE $column=?", [$value]);

        if ($exists) {
            $errors["taken_value"] = lang_f('errors.taken_input', $field);
        }

        return !$exists;
    }
}
