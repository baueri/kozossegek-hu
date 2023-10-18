<?php

namespace Framework;

use Framework\Exception\InvalidTranslationFileException;
use Framework\Support\Collection;

class Translator
{
    private Collection $cache;

    private string $defaultLang = '';

    private array $langSources = [
        ROOT . 'framework' . DS . 'lang' . DS . ':lang' . '.json',
        RESOURCES . 'lang' . DS . ':lang' . '.json'
    ];

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

        report("missing lang key{$lang}): {$key}");

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
        foreach ($this->langSources as $source) {
            $source = str_replace(':lang', $lang, $source);

            if (file_exists($source)) {
                $content = file_get_contents($source);

                if ($parsed = json_decode($content, true)) {
                    $this->cache[$lang] = $parsed;
                    return;
                } else {
                    throw new InvalidTranslationFileException();
                }
            }
        }
    }
}
