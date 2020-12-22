<?php


namespace Framework\Http\View;


use Framework\Event\Event;

class ViewLoaded extends Event
{
    protected static $listeners = [];
    
    public $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }
}