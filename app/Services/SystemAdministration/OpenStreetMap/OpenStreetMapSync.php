<?php

namespace App\Services\SystemAdministration\OpenStreetMap;

use App\Models\Institute;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\OsmInstitutes;
use Framework\Console\Command;
use Framework\Support\Arr;
use GuzzleHttp\Client;

class OpenStreetMapSync implements Command
{
    private const API = 'https://nominatim.openstreetmap.org/search?q=%s&format=json';

    public static function signature(): string
    {
        return 'osm:sync';
    }

    public function handle(): void
    {
        Institutes::query()
            ->where('address', '<>', '')
            ->notDeleted()
            ->withCountWhereHas('groups', fn (ChurchGroups $query) => $query->active())
            ->get()
            ->map(function (Institute $institute) {
                OsmInstitutes::query()->updateOrInsert(
                    [
                    'institute_id' => $institute->getId(),
                    ],
                    [
                        'latlon' => $this->getLatLon($institute),
                        'popup_html' => $this->getHtml($institute)
                    ]
                );
            });

    }

    private function getLatLon(Institute $institute): string
    {
        $response = collect([$institute->address, $institute->name, $institute->name2])->firstNonEmpty(function ($address) use ($institute) {
            return $this->getAddress($institute->city, $address);
        });

        return trim(($response[0]['lat'] ?? '') . ',' . ($response[0]['lon'] ?? ''), ',');
    }

    private function getAddress(string $city, string $address)
    {
        static $client;
        $client ??= new Client();
        $url = sprintf(self::API, "{$city},{$address}");
        return json_decode($client->get($url)->getBody(), true);
    }

    private function getHtml(Institute $institute): string
    {
        return <<<HTML
            <b>$institute->name</b><br/>$institute->address<br/><p>Regisztrált Közösségek száma: <b>$institute->groups_count</b></p>
        HTML;
    }
}