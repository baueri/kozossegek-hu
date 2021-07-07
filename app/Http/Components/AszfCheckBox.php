<?php

namespace App\Http\Components;

class AszfCheckBox
{
    public function render(string $checkboxDirection = 'left'): string
    {
        $message = 'Hozzájárulok, hogy a kozossegek.hu a fent megjelölt adataimat az adatkezelési tájékoztatójában foglaltak szerint kezelje.
                     A kozossegek.hu weboldal <a href="/adatkezelesi-tajekoztato" target="_blank"><b><u>adatkezelési tájékoztatójában</u></b></a> illetve az <a href="/aszf" target="_blank"><b><u>általános szerződési feltételekben</u></b></a> foglaltakat megismertem és elfogadom.';
        $checkbox = '<input type="checkbox" required id="adatkezelesi-tajekoztato">';

        $out = $checkboxDirection === 'left' ? "{$checkbox} {$message}" : "{$message} {$checkbox}";

        return "<label>{$out}</label>";
    }
}
