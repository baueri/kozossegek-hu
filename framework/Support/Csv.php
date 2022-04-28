<?php

namespace Framework\Support;

class Csv
{
    public static function parse(string $filePath, string $separator = ','): array
    {
        return array_map(function($row) use ($separator) { return str_getcsv($row, $separator); }, file($filePath));
    }
}
