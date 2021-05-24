<?php

namespace Framework\Support;

use Framework\Application;
use Framework\Support\Validator\Rules\ExistsRule;
use Framework\Support\Validator\Rules\PasswordRule;
use Framework\Support\Validator\Rules\RequiredRule;
use Framework\Support\Validator\Rules\Rule;
use Framework\Support\Validator\Rules\UniqueRowRule;
use Framework\Support\Validator\ValidatorException;
use Framework\Traits\ManagesErrors;
use InvalidArgumentException;

abstract class Validator
{
    use ManagesErrors;

    protected bool $throwExceptionOnFail = false;

    /**
     * @var string[]|Rule[]
     */
    protected array $rules = [
        RequiredRule::class,
        UniqueRowRule::class,
        PasswordRule::class,
        ExistsRule::class
    ];

    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param array $data
     * @return bool
     * @throws ValidatorException
     */
    final public function validate(array $data): bool
    {
        foreach ($this->getRules() as $field => $fieldRules) {
            $toValidate = Arr::get($data, $field);
            $this->validateField($field, $toValidate, $fieldRules, $data);
        }

        return !$this->hasErrors();
    }

    abstract public function getRules(): array;

    /**
     * @param $field
     * @param $value
     * @param $fieldRules
     * @param $data
     * @throws ValidatorException
     */
    private function validateField($field, $value, $fieldRules, $data)
    {
        $parsedFieldRules = $this->getFieldRules($fieldRules);
        $errors = [];
        foreach ($parsedFieldRules as $fieldRule) {
            $rule = $this->getRule($fieldRule);
            $rule->validate($fieldRule, $field, $value, $data, $errors);
        }
        if ($errors) {
            $this->setError($errors, $field);
            if ($this->throwExceptionOnFail) {
                throw new ValidatorException(key($errors));
            }
        }
    }

    public function messages()
    {
        return [];
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

        throw new InvalidArgumentException(sprintf("rule %s does not exist", $rule));
    }
}
