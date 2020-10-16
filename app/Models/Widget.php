<?php

namespace App\Models;

use App\Components\Widget\WidgetParser;
use Exception;
use Framework\Model\Model;
use App\Components\Widget\TextWidget;

class Widget extends Model
{
    /**
     * @var static[]
     */
    protected static $widgetParsers = [
        TextWidget::class
    ];

    public $id;

    public $type;

    public $uniqid;

    public $data;

    public $name;

    /**
     * @param string|Widget $widgetClass
     */
    public static function registerWidgetType(string $widgetClass)
    {
        if (!in_array($widgetClass, static::$widgetParsers)) {
            static::$widgetParsers[] = $widgetClass;
        }
    }

    /**
     *
     * @return WidgetParser
     * @throws Exception
     */
    private function getWidgetRenderer()
    {
        foreach (static::$widgetParsers as $widgetRenderer) {
            if ($widgetRenderer::getType() == $this->type) {
                return app()->make($widgetRenderer);
            }
        }
        
        throw new Exception('not widget renderer');
    }

    /**
     *
     * @return string
     */
    public function render()
    {
        return $this->getWidgetRenderer()->render($this);
    }
    
    /**
     * 
     * @return \Framework\Support\Collection
     */
    public static function getWidgetParsers()
    {
        return collect(static::$widgetParsers);
    }
}
