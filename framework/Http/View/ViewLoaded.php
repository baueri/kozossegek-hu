<?php

namespace Framework\Http\View;

use Framework\Event\Event;

class ViewLoaded extends Event
{
    protected static $listeners = [];

    public string $filePath;

    public string $cachedFilePath;

    public function __construct(string $filePath, string $cachedFilePath)
    {
        $this->filePath = $filePath;
        $this->cachedFilePath = $cachedFilePath;
    }
}
