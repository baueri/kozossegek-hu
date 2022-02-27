<?php

namespace App\Http\Components;

use Framework\Http\View\Component;

class AszfCheckBox extends Component
{
    public function render(): string
    {
        return <<<EOT
            <label>
                <input type="checkbox" required id="adatkezelesi-tajekoztato">&nbsp;
                Hozzájárulok, hogy a kozossegek.hu a fent megjelölt adataimat az adatkezelési tájékoztatójában foglaltak szerint kezelje.
                A kozossegek.hu weboldal <a href="/adatkezelesi-tajekoztato" target="_blank"><b><u>adatkezelési tájékoztatójában</u></b></a> illetve az <a href="/aszf" target="_blank"><b><u>általános szerződési feltételekben</u></b></a> foglaltakat megismertem és elfogadom.
            </label>
        EOT;
    }
}
