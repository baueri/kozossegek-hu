<?php

namespace App\Enums;

use Exception;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

trait HasTranslation
{
    final public function translate(): string
    {
        $className = substr(static::class, strrpos(static::class, '\\') + 1);

        return lang(StringHelper::snake($className) . '.' . $this->value());
    }

    public static function mapTranslated(): Collection
    {
        if (!method_exists(static::class, 'collect')) {
            throw new Exception('enum class `' . static::class . '` must implement `collect()` method');
        }

        return static::collect()->keyBy(fn ($enum) => $enum->value())->map->translate();
    }
}
