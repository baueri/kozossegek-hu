<?php

namespace App\Components\Widget;
use App\Models\Widget;

class TextWidget implements WidgetRenderer
{
    public static function getType()
    {
        return 'text';
    }

    public static function getName()
    {
        return 'Szövegdoboz';
    }

    public function render(Widget $widget)
    {
        return $widget->data;
    }
}
