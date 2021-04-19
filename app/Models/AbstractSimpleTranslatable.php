<?php

namespace App\Models;

use Framework\Support\StringHelper;

/**
 * Description of AbstractSimpleTranslatable
 *
 * @author ivan
 */
abstract class AbstractSimpleTranslatable
{
    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
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
