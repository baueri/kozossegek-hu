<?php

namespace App\Services\SystemAdministration\OpenStreetMap;

use App\Models\City;
use App\Models\Institute;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Cities;
use App\QueryBuilders\GroupViews;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\OsmMarkers;
use App\Repositories\CityStatistics;
use Framework\Console\Command;

class OpenStreetMapSync extends Command
{
    public static function signature(): string
    {
        return 'osm:sync';
    }

    public function handle(): int
    {
        $this->output->info('Syncing Open Street Map pois...');

        db()->transaction(function () {
            OsmMarkers::query()->delete();

            $groups = fn (ChurchGroups $query) => $query->active();
            Institutes::query()
                ->where('address', '<>', '')
                ->notDeleted()
                ->withCount('groups', $groups)
                ->whereHas('groups', $groups)
                ->get()
                ->map(function (Institute $institute) {
                    OsmMarkers::query()->insert([
                        'type' => 'institute',
                        'latlon' => $institute->latlon(),
                        'popup_html' => addslashes($this->getHtml($institute))
                    ]);
                });

            Cities::query()
                ->select(['name', 'lat', 'lon'])
                ->with('statistics', fn (CityStatistics $query) => $query->selectSums())
                ->withCount('groups', fn (GroupViews $query) => $query->active())
                ->get()
                ->each(function (City $city) {
                    $searches = $city->statistics->search_count ?? 0;
                    $openedGroups = $city->statistics->opened_groups_count ?? 0;
                    $contactGroups = $city?->statistics->contacted_groups_count ?? 0;
                    $totalActivity = $searches + $openedGroups + $contactGroups;
                    if ($totalActivity < CityStatistics::INTERACTION_MIN_WEIGHT && !$city->groups_count) {
                        return;
                    }

                    $marker = '/images/marker_red.png';
                    if ($city->groups_count && $totalActivity) {
                        $marker = '/images/marker_green.png';
                    } elseif ($city->groups_count) {
                        $marker = '/images/marker_blue.png';
                    }
                    OsmMarkers::query()->insert([
                        'marker' => $marker,
                        'type' => 'city_stat',
                        'latlon' => "{$city->lat},{$city->lon}",
                        'popup_html' => <<<HTML
                        <p><b>{$city->name}</b></p>
                        <b>Közösségek:</b> {$city->groups_count}<br/>
                        <b>Keresések:</b> {$searches}<br/>
                        <b>Közi megtekintések:</b> {$openedGroups}<br/>
                        <b>Kapcsolatfelvétel:</b> {$contactGroups}<br/>
                    HTML
                    ]);
                });
        });
        $this->output->success('DONE');

        return self::SUCCESS;
    }

    private function getHtml(Institute $institute): string
    {
        return <<<HTML
        <b>$institute->name</b>
        <br/>
        $institute->city, $institute->address<br/>
        <p>Regisztrált Közösségek száma: <b>$institute->groups_count</b></p>
        <a href="{$institute->groupsUrl('terkep')}">Megnézem</a>
        HTML;
    }
}
