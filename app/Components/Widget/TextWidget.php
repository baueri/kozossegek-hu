<?php

namespace App\Components\Widget;

use App\Models\Widget;

class TextWidget implements WidgetParser
{
    public static function getType(): string
    {
        return 'text';
    }

    public static function getName(): string
    {
        return 'Szövegdoboz';
    }

    public function render(Widget $widget): string
    {
        return $widget->data;
    }

    public function getFormView(): string
    {
        return 'admin.widget.template.text';
    }
}
