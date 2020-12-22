<?php

namespace Framework\Support;

class Csv
{
    /**
     * @param $filePath
     * @param string $separator
     * @return array
     */
    public static function parse($filePath, $separator = ','): array
    {
        return array_map(function($row) use ($separator) { return str_getcsv($row, $separator); }, file($filePath));
    }
}
