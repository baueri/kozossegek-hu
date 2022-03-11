<?php

namespace App\Services\SystemAdministration\OpenStreetMap;

use App\Models\Institute;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\OsmInstitutes;
use Framework\Console\Command;
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
        OsmInstitutes::truncate();

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
                OsmInstitutes::query()->updateOrInsert(
                    [
                    'institute_id' => $institute->getId(),
                    ],
                    [
                        'latlon' => trim(($address['lat'] ?? '') . ',' . ($address['lon'] ?? ''), ','),
                        'popup_html' => addslashes($this->getHtml($institute, $matches[0]))
                    ]
                );
            });

    }

    private function getAddress(string $city, string $address)
    {
        static $client;
        $client ??= new Client();
        $url = sprintf(self::API, "{$city},{$address}");
        return json_decode($client->get($url)->getBody(), true)[0] ?? [];
    }

    private function getHtml(Institute $institute, $zip): string
    {
        return <<<HTML
        <b>$institute->name</b>
        <br/>
        $zip $institute->city, $institute->address<br/>
        <p>Regisztrált Közösségek száma: <b>$institute->groups_count</b></p>
        <a href="{$institute->groupsUrl('terkep')}">Megnézem</a>
        HTML;
    }
}