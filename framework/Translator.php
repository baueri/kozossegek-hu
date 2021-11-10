<?php

namespace Framework;

use Framework\Exception\InvalidTranslationFileException;
use Framework\Support\Collection;

class Translator
{
    private Collection $cache;

    private string $defaultLang = '';

    public function __construct()
    {
        $this->cache = new Collection();
    }

    public function setDefaultLang(string $lang): Translator
    {
        $this->defaultLang = $lang;

        return $this;
    }

    /**
     * @throws InvalidTranslationFileException
     */
    public function translate(string $key, ?string $lang = null): string
    {
        $lang = $lang ?: $this->defaultLang;

        if (!isset($this->cache[$lang])) {
            $this->load($lang);
        }

        return $this->cache[$lang][$key] ?: "?$key?";
    }

    /**
     * @throws InvalidTranslationFileException
     */
    public function translateF(string $key, ...$args): string
    {
        return sprintf($this->translate($key), ...$args);
    }

    /**
     * @throws InvalidTranslationFileException
     */
    private function load(string $lang): void
    {
        $content = file_get_contents(RESOURCES . 'lang' . DS . $lang . '.json');

        if ($parsed = json_decode($content, true)) {
            $this->cache[$lang] = $parsed;
        } else {
            throw new InvalidTranslationFileException();
        }
    }
}
