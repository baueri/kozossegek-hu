<?php

namespace Framework\Translation;

use Framework\Event\EventDisptatcher;
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

        $translated = $this->cache[$lang][$key] ?? null;

        if ($translated) {
            return $translated;
        }

        if ($lang === 'hu') {
            return $key;
        }

        EventDisptatcher::dispatch(new TranslationMissing($lang, $key));

        return "$key";
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
        $fileName = RESOURCES . 'lang' . DS . $lang . '.json';

        if (!file_exists($fileName)) {
            throw new InvalidTranslationFileException("Could not find translation file: {$fileName}");
        }

        $content = file_get_contents(RESOURCES . 'lang' . DS . $lang . '.json');

        if (!is_null($parsed = json_decode($content, true))) {
            $this->cache[$lang] = $parsed;
        } else {
            throw new InvalidTranslationFileException("invalid translation file for: {$lang}");
        }
    }
}
