<?php

namespace App\Enums;

use Framework\Support\StringHelper;

trait HasTranslation
{
    final public function translate(): string
    {
        $className = substr(static::class, strrpos(static::class, '\\') + 1);

        return lang(StringHelper::snake($className) . '.' . $this->name);
    }
}
