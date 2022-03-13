<?php

namespace App\Http\Components;

use App\Enums\OsmType;
use App\Models\OsmMarker;
use App\Models\User;
use App\QueryBuilders\OsmMarkers;
use Framework\Http\View\Component;
use Framework\Support\Arr;
use Framework\Support\Collection;

class OpenStreeMap extends Component
{
    private Collection $types;

    public function __construct($types, public readonly int $height = 500)
    {
        $this->types = collect($types);
    }

    public function render(): string
    {
        $markers = OsmMarkers::query()
            ->when($this->types, fn (OsmMarkers $query) => $query->whereIn('type', $this->types))
            ->get()
            ->map(function (OsmMarker $osm) {
                [$lat, $lon] = Arr::fromList($osm->latlon);
                $popup_html = $osm->popup_html;
                $marker = $osm->marker;
                return compact('lat', 'lon', 'popup_html', 'marker');
            })->toJson();

        $height = $this->height;

        return view('partials.components.open_street_map', compact('markers', 'height'));
    }
}
