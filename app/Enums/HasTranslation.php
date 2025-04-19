<?php

declare(strict_types=1);

namespace App\Enums;

use Exception;
use Framework\Support\Collection;
use Framework\Support\StringHelper;
use Framework\Traits\EnumTrait;

/**
 * @mixin EnumTrait
 */
trait HasTranslation
{
    public function translate(?string $lang = null): string
    {
        $className = substr(static::class, strrpos(static::class, '\\') + 1);

        return lang(StringHelper::snake($className) . '.' . $this->value(), $lang);
    }

    /**
     * @return Collection<string, string>
     * @throws Exception
     */
    public static function mapTranslated(): Collection
    {
        if (!method_exists(static::class, 'collect')) {
            throw new Exception('enum class `' . static::class . '` must implement `collect()` method');
        }

        return static::collect()->keyBy(fn ($enum) => $enum->value())->map->translate();
    }
}
