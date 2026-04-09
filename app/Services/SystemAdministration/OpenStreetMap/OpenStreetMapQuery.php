<?php

declare(strict_types=1);

namespace App\Services\SystemAdministration\OpenStreetMap;

use Framework\Support\Arr;
use GuzzleHttp\Client;

class OpenStreetMapQuery
{
    private const API_BASE = 'https://nominatim.openstreetmap.org/search';

    public function __construct(
        private readonly Client $client
    ) {
    }

    public function search(string $query): array
    {
        $query = trim($query);
        if ($query === '') {
            return [];
        }

        $url = self::API_BASE . '?' . http_build_query([
            'q' => $query,
            'format' => 'json',
            'addressdetails' => '1',
            'limit' => '10',
        ]);

        $response = $this->client->get($url, [
            'headers' => [
                'User-Agent' => 'kozossegek.hu/1.0 (admin event address lookup)',
                'Accept-Language' => 'hu,en',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true) ?? [];
    }

    public function getLatLon(string $query): array
    {
        return Arr::only($this->search($query)[0] ?? [], ['lat', 'lon']);
    }
}
