<?php


namespace Framework\Http\View;


use Framework\Event\Event;

class ViewLoaded extends Event
{
    public $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
}