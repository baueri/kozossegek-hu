<?php

namespace Framework\Support\DataFile;

class PhpDataFile extends DataFile
{
    protected static ?string $extension = 'php';

    protected function parse($content)
    {
        return $content;
    }

    protected static function getContent(string $filename)
    {
        return include $filename;
    }
}
