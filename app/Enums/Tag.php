<?php

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum Tag: string
{
    use HasTranslation;
    use EnumTrait;

    case beszelgeto_kor = 'beszelgeto_kor';
    case bibliaolvaso = 'bibliaolvaso';
    case csalados = 'csalados';
    case dicsoito_zenes = 'dicsoito_zenes';
    case egyedulallok = 'egyedulallok';
    case egyetemista = 'egyetemista';
    case elmelkedo = 'elmelkedo';
    case ferfi_kor = 'ferfi_kor';
    case imakor = 'imakor';
    case karitativ = 'karitativ';
    case karizmatikus = 'karizmatikus';
    case katekumen = 'katekumen';
    case klub = 'klub';
    case korus = 'korus';
    case kozepiskolas = 'kozepiskolas';
    case noi_kor = 'noi_kor';
    case onsegito_kor = 'onsegito_kor';
    case sportos = 'sportos';
    case szemlelodo = 'szemlelodo';
    case szintarsulat = 'szintarsulat';
    case utkereso = 'utkereso';
    case exodus = 'exodus';

    public function icon(string $class = ''): string
    {
        $class = trim("tag-img tag-{$this->value} {$class}");
        return "<span class='{$class}' title='{$this->translate()}'></span>";
    }
}
