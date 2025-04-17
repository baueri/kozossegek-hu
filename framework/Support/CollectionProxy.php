<?php

declare(strict_types=1);

namespace Framework\Support;

readonly class CollectionProxy
{
    public function __construct(
        private Collection $collection,
        private string $collectionMethod
    ) {
    }

    public function __get(string $name)
    {
        return $this->collection->{$this->collectionMethod}(fn ($item) => is_array($item) ? $item[$name] : $item->{$name});
    }

    public function __call(string $method, $arguments)
    {
        return $this->collection->{$this->collectionMethod}(fn ($item) => $item->{$method}(...$arguments));
    }
}
