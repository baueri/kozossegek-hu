<?php


namespace Framework\Support\Validator\Rules;


use Framework\Database\Database;

class ExistsRule implements Rule
{

    /**
     * @var Database
     */
    private $db;

    /**
     * ExistsRule constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        return 'exists';
    }

    /**
     * @param $rule
     * @param string $field
     * @param $value
     * @param array $inputs
     * @param array $errors
     * @return bool
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

        $exists = (bool) $this->db->first("SELECT 1 as `exists` FROM $table WHERE $column=?", [$value]);

        if (!$exists) {
            $errors["row_not_exist"] = lang_f('errors.row_not_exist', $table);
        }

        return $exists;
    }
}