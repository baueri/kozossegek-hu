<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Components\Widget;

use Framework\Support\StringHelper;

/**
 * Description of WidgetHelper
 *
 * @author ivan
 */
class WidgetHelper
{

    /**
     *
     * @param string $name
     * @return string
     */
    public static function generateUniqId($name)
    {

        [$word1, $word2] = explode(' ', StringHelper::convertSpecialChars($name));
        if (!$word2) {
            return strtoupper(substr($word1, 0, 4));
        }

        return strtoupper(substr($word1, 0, 2) . substr($word2, 0, 2));
    }
}
