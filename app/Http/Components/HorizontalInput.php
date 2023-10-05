<?php

declare(strict_types=1);

namespace App\Http\Components;

use Framework\Http\View\TagComponent;
use phpseclib3\Crypt\RSA\Formats\Keys\OpenSSH;

abstract class HorizontalInput extends TagComponent
{
    public function __construct(
        protected readonly string $label = '',
        protected readonly string $required = '',
        protected readonly string $info = '',
        protected readonly string $size = ''
    ) {
    }

    abstract protected function getFormField(): string;

    public function render(): string
    {
        $requiredStar = $this->required ? '<span class="has-text-danger">*</span>' : '';
        $infoTooltip = $this->info ? " <span class=\"icon is-small is-right\" data-tooltip=\"{$this->info}\"><i class=\"fas fa-info-circle\"></i></span>" : "";
        return <<<HTML
            <div class="field">
                <div class="field-label has-text-left">
                    <label class="label">{$this->label}{$requiredStar}{$infoTooltip}</label>
                </div>
                <div class="field-body">
                    <div class="field {$this->size}">
                        {$this->getFormField()}
                    </div>
                </div>
            </div>
        HTML;
    }

}