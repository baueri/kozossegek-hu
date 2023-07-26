<?php

declare(strict_types=1);

namespace App\Portal\BreadCrumb;

use Framework\Http\View\Component;

class BreadCrumb extends Component
{
    public function __construct(
        private readonly array $breadcrumbs
    ) {
    }

    public function render(): string
    {
        return view('partials.components.breadcrumb', ['breadcrumbs' => $this->breadcrumbs]);
    }
}