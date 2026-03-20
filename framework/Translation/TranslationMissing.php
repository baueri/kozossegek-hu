<?php

declare(strict_types=1);

namespace Framework\Translation;

use Framework\Event\Event;

class TranslationMissing extends Event
{
    protected static array $listeners = [];

    public function __construct(
      public readonly ?string $lang,
      public readonly string $key
    ) {
    }
}
