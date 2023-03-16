<?php

declare(strict_types=1);

namespace Framework\Support\Validator\Rules;

use Framework\Database\Database;

class ExistsRule implements Rule
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public static function getName(): string
    {
        return 'exists';
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

        $exists = (bool) $this->db->first("SELECT 1 as `exists` FROM $table WHERE $column=?", [$value]);

        if (!$exists) {
            $errors["row_not_exist"] = lang_f('errors.row_not_exist', $table);
        }

        return $exists;
    }
}
