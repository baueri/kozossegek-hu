<?php

declare(strict_types=1);

namespace App\Services\SystemAdministration\OpenStreetMap;

use Framework\Support\Arr;
use GuzzleHttp\Client;

class OpenStreetMapQuery
{
    private const API_URL = 'https://nominatim.openstreetmap.org/search?q=%s&format=json';

    public function __construct(
        private readonly Client $client
    ) {
    }

    public function search(string $query): array
    {
        $url = sprintf(self::API_URL, $query);
        return json_decode($this->client->get($url)->getBody()->getContents(), true) ?? [];
    }

    public function getLatLon(string $query): array
    {
        return Arr::only($this->search($query)[0] ?? [], ['lat', 'lon']);
    }
}
