<?php


namespace Framework\Support;


use Framework\Application;
use Framework\Support\Validator\Rules\ExistsRule;
use Framework\Support\Validator\Rules\PasswordRule;
use Framework\Support\Validator\Rules\RequiredRule;
use Framework\Support\Validator\Rules\Rule;
use Framework\Support\Validator\Rules\UniqueRowRule;
use Framework\Traits\ManagesErrors;

abstract class Validator
{
    use ManagesErrors;

    /**
     * @var string[]|Rule[]
     */
    protected $rules = [
        RequiredRule::class,
        UniqueRowRule::class,
        PasswordRule::class,
        ExistsRule::class
    ];

    /**
     * @var Application
     */
    private $app;

    /**
     * Validator constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param array $data
     * @return bool
     */
    final public function validate(array $data)
    {
        foreach ($this->getRules() as $field => $fieldRules) {
            $toValidate = $data[$field] ?? null;
            $this->validateField($field, $toValidate, $fieldRules, $data);
        }

        return $this->hasErrors();
    }

    abstract public function getRules(): array;

    private function validateField($field, $value, $fieldRules, $data)
    {
        $parsedFieldRules = $this->getFieldRules($fieldRules);
        $errors = [];
        foreach ($parsedFieldRules as $fieldRule) {
            $rule = $this->getRule($fieldRule);
            $rule->validate($fieldRule, $field, $value, $data, $errors);

        }
        if ($errors) {

            $this->pushError($errors, $field);
        }

    }

    private function getFieldRules(string $fieldRules)
    {
        return explode('|', $fieldRules);
    }

    /**
     * @param $ruleKey
     * @return Rule
     */
    private function getRule($ruleKey)
    {
        foreach ($this->rules as $rule) {
            if (preg_match('/^(' . $rule::getName() . ')(:.*)?/', $ruleKey)) {
                return $this->app->make($rule);
            }
        }

        throw new \InvalidArgumentException(sprintf("rule %s does not exist", $rule));
    }
}