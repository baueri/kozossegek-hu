<?php

namespace App\Http\Components;

use App\Enums\OsmType;
use App\Models\OsmMarker;
use App\QueryBuilders\OsmMarkers;
use Framework\Http\View\Component;
use Framework\Support\Arr;

class OpenStreeMap extends Component
{
    public function __construct(
        public readonly OsmType $type
    ) {
    }

    public function render(): string
    {
        $markers = OsmMarkers::query()
            ->when($this->type, fn (OsmMarkers $query) => $query->where('type', $this->type->name))
            ->get()
            ->map(function (OsmMarker $osm) {
                [$lat, $lon] = Arr::fromList($osm->latlon);
                $popup_html = $osm->popup_html;
                return compact('lat', 'lon', 'popup_html');
            })->toJson();

        return view('partials.components.open_street_map', compact('markers'));
    }
}
