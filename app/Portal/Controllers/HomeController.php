<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\Enums\AgeGroup;

class HomeController extends PortalController
{
    public function home(): string
    {
        $age_groups = AgeGroup::cases();
        $intro = $this->getIntro();
        $selected_age_group = null;

        return view('portal.home', compact('age_groups', 'selected_age_group', 'intro'));
    }

    private function getIntro(): string
    {
        if (getLang() === 'hu') {
            return <<<HTML
            <h2 class="mb-4 title-secondary">Találd meg a közösséged!</h2>
            <p>A kozossegek.hu egy katolikus közösségkereső portál, amelyet azért hoztunk létre, hogy segítsünk mindenkinek megtalálni a közösségét akárhol is éljen, tanuljon, vagy dolgozzon, nemtől, kortól, életállapottól függetlenül. Hisszük, hogy az ember alapszükséglete a közösséghez tartozás, hiszen ezáltal tud önmaga lenni, így tud megbirkózni az élet nehézségeivel, így válhat az élete teljessé.</p>
            <p>Kívánjuk, hogy ismerd fel azt az erőt, amely a keresztény közösségekben rejlik, találd meg saját helyedet és légy aktív tagja az Egyháznak!</p>
            <p><strong>"Ahol ugyanis ketten vagy hárman összegyűlnek a nevemben, ott vagyok közöttük.” Mt.18,20</strong></p>
            HTML;
        }

        return <<<HTML
        <h2 class="mb-4 title-secondary">Find your church group!</h2>
        <p>kozossegek.hu is a Catholic portal for church groups <b>based in Hungary</b>. It is created to help everyone find their church groups wherever they live, study, or work, regardless of gender, age, or life situation. We believe that belonging to a community is a fundamental human need, as it allows individuals to be themselves, cope with life's challenges, and make their lives complete.</p>
        <p>We hope you recognize the strength that lies within Christian communities, find your own place, and become an active member of the Catholic Church!</p>
        <p><strong>"For where two or three gather in my name, there am I with them.” Matthew 18:20</strong></p>
        HTML;
    }
}
