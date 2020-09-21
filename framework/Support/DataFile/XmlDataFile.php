<?php


namespace Framework\Support\DataFile;


use Framework\Misc\XmlObject;

class XmlDataFile extends DataFile
{
    /**
     * @var string
     */
    protected static $extension = 'xml';

    /**
     * @param $content
     * @return XmlObject
     */
    protected function parse($content)
    {
        return new XmlObject($content);
    }
}