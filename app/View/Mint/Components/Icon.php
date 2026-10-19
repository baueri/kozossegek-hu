<?php

declare(strict_types=1);

namespace App\View\Mint\Components;

use App\Http\Components\FontawesomeIcon;
use Baueri\Mint\Context;
use Baueri\Mint\Module\Module;

class Icon extends Module
{
    public function render(Context $context): string
    {
        $name = (string) $context->resolve('name', '');
        $title = (string) $context->resolve('title', '');
        $additionalClass = (string) (
            $context->resolve('additionalClass')
            ?? $context->resolve('additional-class')
            ?? ''
        );

        return (new FontawesomeIcon)->render($name, $title, $additionalClass);
    }
}
