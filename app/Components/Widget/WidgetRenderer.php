<?php

namespace App\Components\Widget;

use App\Models\Widget;


interface WidgetRenderer
{

    public static function getType();

    public static function getName();

   /**
    * @return string
    */
    public function render(Widget $widget);
}
