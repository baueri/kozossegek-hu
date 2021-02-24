<?php


namespace Framework\Support\Validator\Rules;


use Framework\Database\Database;

class UniqueRowRule implements Rule
{
    /**
     * @var Database
     */
    private $database;

    /**
     * UniqueRowRule constructor.
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return 'unique';
    }

    /**
     * @inheritDoc
     */
    public function validate($rule, $field, $value, $inputs = [], &$errors = [])
    {
        if (!$value) {
            return false;
        }

        [, $table] = explode(':', $rule, 2);

        $column = $field;

        if (strpos($table, '.') !== false) {
            [$table, $column] = explode('.', $table);
        }

        $exists = (bool) $this->database->first("SELECT 1 as `exists` FROM $table WHERE $column=?", [$value]);

        if ($exists) {
            $errors["taken_value"] = lang_f('errors.taken_input', $field);
        }

        return !$exists;
    }
}
