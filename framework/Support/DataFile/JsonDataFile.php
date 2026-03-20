<?php


namespace Framework\Support\DataFile;

class JsonDataFile extends DataFile
{
    /**
     * @var string
     */
    protected static ?string $extension = 'json';

    /**
     * @param $content
     * @return array
     */
    protected function parse($content)
    {
        return (array)json_decode($content, true);
    }
}
