<?php

declare(strict_types=1);

namespace Framework\File;

use Framework\Support\Collection;

readonly class Path
{
    public function __construct(
        private string $path
    ) {
    }

    public function storage(string $path): self
    {
        return new self(app()->root('storage/' . $path));
    }

    public function public(string $path): self
    {
        return new self(app()->root('public/' . $path));
    }

    public function move(string $oldPath, string $newPath): bool
    {
        return rename($this->path . $oldPath, $this->path . $newPath);
    }

    public function delete(string $path): bool
    {
        return unlink($this->path . $path);
    }

    public function exists(string $path): bool
    {
        return file_exists($this->path . $path);
    }

    public function save(string $path, string $content, int $flags = 0): bool
    {
        return file_put_contents($this->path . $path, $content, $flags) !== false;
    }

    public function get(string $path): string|false
    {
        return file_get_contents($this->path . $path);
    }

    public function parseList(string $path, string $delimiter = ','): Collection
    {
        return Collection::fromList($this->get($path), $delimiter);
    }
}
