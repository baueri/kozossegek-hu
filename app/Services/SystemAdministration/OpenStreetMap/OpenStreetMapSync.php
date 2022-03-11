<?php

namespace App\Services\SystemAdministration\OpenStreetMap;

use App\Models\Institute;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\OsmMarkers;
use Framework\Console\Command;

class OpenStreetMapSync implements Command
{
    public function __construct(
        private readonly OpenStreetMapQuery $openStreetMapQuery
    ) {
    }

    public static function signature(): string
    {
        return 'osm:sync';
    }

    public function handle(): void
    {
        OsmMarkers::truncate();

        Institutes::query()
            ->where('address', '<>', '')
            ->notDeleted()
            ->withCountWhereHas('groups', fn (ChurchGroups $query) => $query->active())
            ->get()
            ->map(function (Institute $institute) {
                $address = collect([$institute->address, $institute->name, $institute->name2])->firstNonEmpty(function ($address) use ($institute) {
                    return $this->getAddress($institute->city, $address);
                });
                preg_match('/([\d]{4})/', $address['display_name'], $matches);
                OsmMarkers::query()->insert([
                    'latlon' => trim(($address['lat'] ?? '') . ',' . ($address['lon'] ?? ''), ','),
                    'popup_html' => addslashes($this->getHtml($institute, $matches[0]))
                ]);
            });

    }

    private function getAddress(string $city, string $address)
    {
        return $this->openStreetMapQuery->search("{$city},{$address}")[0] ?? [];
    }

    private function getHtml(Institute $institute, $zip): string
    {
        return <<<HTML
            <b>$institute->name</b>
            <br/>
            $zip $institute->city, $institute->address<br/>
            <p>Regisztrált Közösségek száma: <b>$institute->groups_count</b></p>
            <a href="{$institute->groupsUrl()}">Megnézem</a>
        HTML;
    }
}
