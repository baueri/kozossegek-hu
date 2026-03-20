<?php

namespace App\Models;

use Framework\Support\StringHelper;

abstract class AbstractSimpleTranslatable
{
    public function __construct(
        public string $name
    ) {
    }

    final public function translate(): string
    {
        $className = substr(static::class, strrpos(static::class, '\\') + 1);

        return lang(StringHelper::snake($className) . '.' . $this->name);
    }

    public function __toString(): string
    {
        return $this->translate();
    }
}
