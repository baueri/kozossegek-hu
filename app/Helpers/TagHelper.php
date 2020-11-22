<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Helpers;

/**
 * Description of TagHelper
 *
 * @author ivan
 */
class TagHelper {
    
    public static function getImagePath($tag)
    {
        return "/images/tag/$tag.png";
    }
}
