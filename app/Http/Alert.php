<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http;

/**
 * Description of Alert
 *
 * @author ivan
 */
class Alert
{
    public function render($message, $level = 'info')
    {
        return view('partials.alert', compact('message', 'level'));
    }
}
