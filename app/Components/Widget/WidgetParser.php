<?php

namespace App\Components\Widget;

use App\Models\Widget;

interface WidgetParser
{
    public static function getType(): string;

    public static function getName(): string;

    public function getFormView(): string;

    public function render(Widget $widget): string;
}
