<?php

namespace Framework\File;

class Image extends File
{
    public static function getDimensions(File $file)
    {
        list($width, $height) = getimagesize($file->getFilePath());
        return compact('width', 'height');
    }
}