<?php

namespace Framework\Support\DataFile;

abstract class DataFile
{
    const ROOT = ROOT;

    protected static ?string $basePath = null;

    protected static ?string $extension = null;

    protected array $items;

    public function __construct(?string $fileName = null)
    {
        if ($fileName) {
            $this->load($fileName);
        }
    }

    public function get($key, $default = null)
    {
        [$baseName, $index] = static::parseKey($key);

        $fileName = static::getFileName($baseName);

        $this->load($baseName);

        return static::getValue($this->items[$fileName], $index, $default);
    }

    protected static function parseKey(string $params): array
    {
        $parsed = explode('.', $params, 2);
        if (!isset($parsed[1])) {
            $parsed[] = null;
        }
        return $parsed;
    }

    protected static function getFileName(string $baseName): string
    {
        $scope = '';

        if (str_contains($baseName, '::')) {
            $scope = substr($baseName, 0, strpos($baseName, '::')) . DS;
            $baseName = substr($baseName, strpos($baseName, '::') + 2);
        }

        return static::getDir($scope) . $baseName . (static::$extension ? '.' . static::$extension : '');
    }

    protected static function getDir(string $scope): string
    {
        return self::ROOT . $scope . static::$basePath;
    }

    abstract protected function parse($content);

    protected static function getContent(string $filename)
    {
        return file_get_contents($filename);
    }

    private static function getValue(array $items, mixed $index = null, mixed $default = null): mixed
    {
        if (!$index) {
            return $items ?: $default;
        }

        if (isset($items[$index])) {
            return $items[$index];
        }

        $nestedKeys = explode('.', $index);

        foreach ($nestedKeys as $key) {
            $items = $items[$key] ?? null;
        }

        return $items ?: $default;
    }

    public function load($baseName): self
    {
        $fileName = static::getFileName($baseName);
        if (!isset($this->items[$fileName])) {
            $this->items[$fileName] = static::parse(static::getContent($fileName));
        }

        return $this;
    }

    public function __toString()
    {
        return json_encode($this->items);
    }
}