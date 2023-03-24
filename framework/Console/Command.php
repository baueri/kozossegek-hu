<?php

namespace Framework\Console;

use Framework\Support\StringHelper;

abstract class Command
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    private array $args = [];

    public readonly CommandOutput $output;

    abstract public static function signature(): string;

    abstract public function handle();

    public function __construct()
    {
        $this->output = new CommandOutput();
    }

    public function withArgs(array|string $args): static
    {
        $this->args = array_merge($this->args, (array) $args);
        return $this;
    }

    public function silent(bool $silent = true): static
    {
        $this->output->silent($silent);
        return $this;
    }

    protected function getArguments(): array
    {
        return array_values(
            array_filter($this->args, fn ($arg) => !str_starts_with($arg, '--'))
        );
    }

    protected function getOption($key)
    {
        if (!$this->args) {
            return null;
        }

        if (is_numeric($key)) {
            return $this->args[$key] ?? null;
        }

        foreach ($this->args as $arg) {
            if (StringHelper::startsWith($arg, "--{$key}=")) {
                return mb_substr($arg, mb_strlen("--{$key}="));
            }

            if (StringHelper::startsWith($arg, "--{$key}")) {
                return true;
            }
        }

        return null;
    }

    public function getOptions(): array
    {
        if (!$this->args) {
            return [];
        }

        $options = [];

        foreach ($this->args as $arg) {
            if (preg_match('/--([a-z0-9\-_]+)(?:=([a-z0-9\-_]+))?/', $arg, $matches)) {
                $options[$matches[1]] = $matches[2] ?? true;
            }
        }

        return $options;
    }
}
