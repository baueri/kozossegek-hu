<?php


namespace Framework;


use Framework\Exception\InvalidTranslationFileException;
use Framework\Support\Collection;
use Framework\Support\DataFile\JsonDataFile;

class Translator
{
    protected $cache;

    protected $defaultLang;

    public function __construct()
    {
        $this->cache = new Collection();
    }

    public function setDefaultLang($lang)
    {
        $this->defaultLang = $lang;

        return $this;
    }

    public function translate($key, $lang = null)
    {
        $lang = $lang ?: $this->defaultLang;

        if (!isset($this->cache[$lang])) {
            $this->load($lang);
        }

        return $this->cache[$lang][$key] ?: "?$key?";
    }

    public function translate_f($key, ...$args)
    {
        return sprintf($this->translate($key), ...$args);
    }

    private function load($lang)
    {
        $content = file_get_contents(RESOURCES . 'lang' . DS . $lang . '.json');

        if($parsed = json_decode($content, true)) {
            $this->cache[$lang] = $parsed;
        } else {
            throw new InvalidTranslationFileException();
        }
    }
}