<?php

namespace App\Http\Components;

use App\Models\OsmInstitute;
use App\QueryBuilders\OsmInstitutes;
use Framework\Http\View\Component;
use Framework\Support\Arr;

class OpenStreeMap extends Component
{
    public function render(): string
    {
        $markers = OsmInstitutes::query()
            ->get()
            ->map(function (OsmInstitute $osm) {
                [$lat, $lon] = Arr::fromList($osm->latlon);
                $popup_html = $osm->popup_html;
                return compact('lat', 'lon', 'popup_html');
            })->toJson();

        return view('partials.components.open_street_map', compact('markers'));
    }
}