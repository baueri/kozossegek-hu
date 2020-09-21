<?php


namespace Framework\Support\DataFile;


class PhpDataFile extends DataFile
{
    protected static $extension = 'php';

    /**
     * @param $content
     * @return array
     */
    protected function parse($content)
    {
        return $content ?: null;
    }

    /**
     * @param $filename
     * @return array
     */
    protected static function getContent($filename)
    {
        return include $filename;
    }
}