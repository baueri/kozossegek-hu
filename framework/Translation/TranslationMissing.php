<?php

declare(strict_types=1);

namespace Framework\Translation;

use Framework\Event\Event;

class TranslationMissing extends Event
{
    public function __construct(
      public readonly ?string $lang,
      public readonly string $key
    ) {
    }
}