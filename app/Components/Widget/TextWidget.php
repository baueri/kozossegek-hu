<?php

namespace App\Components\Widget;

use App\Models\Widget;

class TextWidget implements WidgetParser
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

    public function getFormView(): string
    {
        return 'admin.widget.template.text';
    }

    public function prepareDataForSave(&$data): string {
        $data['data'] = $data['w_text'];
    }

}
