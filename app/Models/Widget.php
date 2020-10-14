<?php

namespace App\Models;
use Framework\Model\Model;

class Widget extends Model
{
   /**
    * @var static[]
    */
    protected static $widgetTypes = [];

    public $id;

    public $type;

    public $slug;

    public $data;

    public $name;

   /**
    * @param string|Widget $widgetClass
    */
    public static function registerWidgetType(string $widgetClass)
    {
        static::$widgetTypes[$widgetClass::getType()] = $widgetClass;
    }

    
    private function getWidgetRenderer()
    {
        if (isset(static::$widgetTypes[$this->type])) {
            $renderer = static::$widgetTypes[$this->type];
            return app()->make($renderer);
        }
    }

    public function render()
    {
        return $this->getWidgetRenderer()->render($this);
    }


}
