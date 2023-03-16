<?php

declare(strict_types=1);

namespace Framework\Traits;

use Framework\Support\Collection;

trait ManagesErrors
{
    protected ?Collection $errors = null;

    public function getErrors(): Collection
    {
        return $this->errors ??= collect();
    }

    public function hasErrors(): bool
    {
        return $this->getErrors()->isNotEmpty();
    }

    public function firstError()
    {
        return $this->getErrors()->first();
    }

    public function setError($error, $key = null): void
    {
        $this->getErrors()->set($key, $error);
    }

    public function pushError($error): void
    {
        $this->getErrors()->push($error);
    }
}
