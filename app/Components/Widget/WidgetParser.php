<?php

namespace App\Components\Widget;

use App\Models\Widget;

interface WidgetParser
{
    
    /**
     * @return string
     */
    public static function getType();

    /**
     * @return string
     */
    public static function getName();

    /**
     * @return string
     */
    public function getFormView();

    /**
     * @return string
     */
    public function render(Widget $widget);
    
    /**
     * @param type $data
     */
    public function prepareDataForSave(&$data);
}
