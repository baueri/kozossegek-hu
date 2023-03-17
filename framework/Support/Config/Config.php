<?php

declare(strict_types=1);

namespace Framework\Support\Config;

use Framework\Support\DataFile\PhpDataFile;

class Config extends PhpDataFile
{
    protected static ?string $basePath = 'config' . DS;
}
