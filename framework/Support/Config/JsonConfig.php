<?php

namespace Framework\Support\Config;

use Framework\Support\DataFile\JsonDataFile;

class JsonConfig extends JsonDataFile
{
    protected static ?string $basePath = 'config' . DS;
}
