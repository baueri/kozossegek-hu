<?php

declare(strict_types=1);

namespace App\Http\Components;

use App\Models\ChurchGroupView;
use App\Models\Institute;
use Framework\Http\View\Component;
use Framework\Support\Arr;

class BreadCrumb extends Component
{
    public function __construct(
        private readonly ChurchGroupView $churchGroup,
        private readonly ?Institute $institute
    ) {
    }

    public function render(): string
    {
        $breadCrumbs = $this->getBreadCrumbs();
        return view('partials.components.breadcrumb', ['breadcrumbs' => $breadCrumbs]);
    }

    private function getBreadCrumbs()
    {
        $referer = Arr::get($_SERVER, 'HTTP_REFERER');
        if ($referer && str_contains($referer, get_site_url() . '/kozossegek')) {
            $root = ['name' => 'Közösségek', 'position' => 1, 'url' => $referer];
        } else {
            $root = ['name' => 'Közösségek', 'position' => 1, 'url' => route('portal.groups')];
        }
        $breadCrumbs = [$root];

        $breadCrumbs[] = [
            'name' => $this->churchGroup->city,
            'position' => 2,
            'url' => route('portal.groups', ['varos' => $this->churchGroup->city])
        ];

        $breadCrumbs[] = [
            'name' => $this->churchGroup->institute_name,
            'position' => 3,
            'url' => $this->institute?->groupsUrl()
        ];

        $breadCrumbs[] = [
            'name' => $this->churchGroup->name,
            'position' => 4
        ];

        return $breadCrumbs;
    }
}