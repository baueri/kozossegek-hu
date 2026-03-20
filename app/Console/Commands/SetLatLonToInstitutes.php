<?php

namespace App\Console\Commands;

use App\QueryBuilders\Institutes;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapQuery;
use Framework\Console\Command;

class SetLatLonToInstitutes extends Command
{
    public static function signature(): string
    {
        return 'institute:lat-lon';
    }

    public static function description(): string
    {
        return 'institutes tablaban javitja a lat lon koordinatakat';
    }

    public function handle(): void
    {
        $osm = resolve(OpenStreetMapQuery::class);
        $institutes = Institutes::query()
            ->where('lat', '')
            ->notDeleted()
            ->get();

        foreach ($institutes as $institute) {
            $latlon = collect([$institute->address, $institute->name, $institute->name2, 'templom'])->filter()->firstNonEmpty(function ($address) use ($osm, $institute) {
                return $osm->getLatLon("{$institute->city},{$address}");
            });
            if ($latlon) {
                Institutes::query()->save($institute, $latlon);
            }
        }
    }
}
