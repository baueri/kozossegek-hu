<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Admin\Event\EventTable;
use App\Auth\Auth;
use App\Enums\EventLifeCycle;
use App\Enums\EventStatus;
use App\Models\Event;
use App\QueryBuilders\Events;
use App\Storage\Base64Image;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapQuery;
use Framework\Http\Message;
use Framework\Support\StringHelper;

class EventController extends AdminController
{
    public function __construct(
        \Framework\Http\Request $request,
        private readonly Events $repository
    ) {
        parent::__construct($request);
    }

    public function index(EventTable $table): string
    {
        $filter = $this->request->only('search', 'status', 'lifecycle', 'date_from', 'date_to');

        $current_page = match (true) {
            ($filter['lifecycle'] ?? null) === 'active' => 'active',
            ($filter['lifecycle'] ?? null) === 'cancelled' => 'cancelled',
            default => 'all',
        };

        $statuses = EventStatus::mapTranslated()->all();
        $lifecycles = EventLifeCycle::mapTranslated()->all();

        return view('admin.event.list', compact('table', 'filter', 'current_page', 'statuses', 'lifecycles'));
    }

    public function create(): string
    {
        $event = new Event();
        $action = route('admin.event.do_create');

        return view('admin.event.create', compact('event', 'action'));
    }

    public function doCreate(): never
    {
        $data = $this->validatedData();
        $data['user_id'] = Auth::user()->id;

        if (!$data['slug']) {
            $data['slug'] = $this->makeUniqueSlug($data['name']);
        }

        if ($path = $this->persistFeaturedImage((string) $this->request->get('featured_image_data', ''))) {
            $data['featured_image'] = $path;
        }

        $tags = $this->normalizeTags((string) ($this->request->get('tags') ?? ''));
        unset($data['tags']);

        $event = $this->repository->create($data);
        $this->syncTags((int) $event->id, $tags);

        Message::success('Esemény létrehozva');
        redirect_route('admin.event.edit', ['id' => $event->id]);
    }

    public function edit(): string
    {
        $event = $this->repository->find($this->request['id']);
        if (!$event) {
            Message::danger('A keresett esemény nem található');
            redirect_route('admin.event.index');
        }

        $action = route('admin.event.update', ['id' => $event->id]);
        $tags = collect(builder('event_tags')->where('event_id', $event->id)->pluck('tag'))->all();
        $tags = implode(', ', array_filter($tags));

        return view('admin.event.edit', compact('event', 'action', 'tags'));
    }

    public function update(): never
    {
        $event = $this->repository->findOrFail($this->request['id']);
        $data = $this->validatedData();

        if (!$data['slug']) {
            $data['slug'] = $this->makeUniqueSlug($data['name'], (int) $event->id);
        }

        if ($path = $this->persistFeaturedImage((string) $this->request->get('featured_image_data', ''))) {
            $data['featured_image'] = $path;
        }

        $tags = $this->normalizeTags((string) ($this->request->get('tags') ?? ''));
        unset($data['tags']);

        $this->repository->save($event, $data);
        $this->syncTags((int) $event->id, $tags);

        Message::success('Esemény frissítve');
        redirect_route('admin.event.edit', ['id' => $event->id]);
    }

    public function toggleLifecycle(): never
    {
        $event = $this->repository->findOrFail($this->request['id']);

        $new = $event->lifecycle === 'cancelled' ? 'active' : 'cancelled';
        $this->repository->save($event, ['lifecycle' => $new]);

        Message::success(lang('event_life_cycle.updated'));
        redirect($this->request->referer());
    }

    public function delete(): never
    {
        $event = $this->repository->findOrFail($this->request['id']);
        $this->repository->hardDeleteModel($event);

        Message::warning('Esemény törölve');
        redirect($this->request->referer());
    }

    /**
     * Nominatim (OpenStreetMap) forward search for admin event address fields.
     *
     * @return array{success: bool, results?: list<array<string, mixed>>, msg?: string}
     */
    public function geocodeSearch(OpenStreetMapQuery $osm): array
    {
        $q = trim((string) $this->request->get('q', ''));
        if (mb_strlen($q) < 3) {
            return api()->error('Írj be legalább 3 karaktert a kereséshez.');
        }

        try {
            $items = $osm->search($q);
        } catch (\Throwable) {
            return api()->error('A címkeresés átmenetileg nem elérhető. Próbáld újra később.');
        }

        $results = [];
        foreach (array_slice($items, 0, 10) as $row) {
            if (empty($row['lat']) || empty($row['lon'])) {
                continue;
            }
            $addr = $row['address'] ?? [];
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                $name = trim((string) ($addr['amenity'] ?? $addr['building'] ?? $addr['shop'] ?? $addr['tourism'] ?? ''));
            }
            $results[] = [
                'label' => (string) ($row['display_name'] ?? ''),
                'lat' => (string) $row['lat'],
                'lng' => (string) $row['lon'],
                'name' => $name,
                'address' => $this->formatNominatimAddressLine($row),
            ];
        }

        return api()->ok(['results' => $results]);
    }

    private function validatedData(): array
    {
        $data = $this->request->only(
            'name',
            'slug',
            'description',
            'starts_at',
            'ends_at',
            'all_day',
            'status',
            'lifecycle',
            'organizer',
            'institute_id',
            'location_name',
            'address',
            'lat',
            'lng',
        );

        $data['name'] = trim((string) ($data['name'] ?? ''));
        $data['slug'] = trim((string) ($data['slug'] ?? ''));
        $data['description'] = (string) ($data['description'] ?? '');

        $data['all_day'] = (int) ($data['all_day'] ?? false);
        $data['ends_at'] = $data['ends_at'] ?: null;
        $data['institute_id'] = $data['institute_id'] ?? null;

        $data['status'] = $data['status'] ?: 'draft';
        $data['lifecycle'] = $data['lifecycle'] ?: 'active';

        return $data;
    }

    /**
     * Hungarian-style single line: "{postcode} {city}, {road} {house_number}"
     * e.g. 6724 Szeged, Sárosi utca 5
     */
    private function formatNominatimAddressLine(array $row): string
    {
        $a = $row['address'] ?? [];
        $postcode = trim((string) ($a['postcode'] ?? ''));
        $city = trim((string) ($a['city'] ?? $a['town'] ?? $a['village'] ?? $a['municipality'] ?? ''));

        $road = trim((string) ($a['road'] ?? ''));
        $houseNumber = trim((string) ($a['house_number'] ?? ''));
        $streetLine = trim(implode(' ', array_filter([$road, $houseNumber], static fn ($p) => $p !== '')));

        $zipCity = trim(implode(' ', array_filter([$postcode, $city], static fn ($p) => $p !== '')));

        if ($zipCity !== '' && $streetLine !== '') {
            return $zipCity . ', ' . $streetLine;
        }
        if ($zipCity !== '') {
            return $zipCity;
        }
        if ($streetLine !== '') {
            return $streetLine;
        }

        return (string) ($row['display_name'] ?? '');
    }

    private function persistFeaturedImage(string $base64): ?string
    {
        $base64 = trim($base64);
        if ($base64 === '') {
            return null;
        }

        $image = new Base64Image($base64);
        $hash = substr(hash('SHA256', $base64), 0, 16);
        $path = env('STORAGE_PATH') . 'public' . DS . 'event' . DS . substr($hash, 0, 2) . DS . substr($hash, 2, 2) . DS . $hash . '.jpg';

        try {
            $image->saveImage($path);
            return $path;
        } catch (\Throwable) {
            // Fallback for misconfigured/unwritable STORAGE_PATH (e.g. local docker permissions)
            $publicRel = 'images/event/' . $hash . '.jpg';
            $publicFsPath = root()->public($publicRel)->path();
            $image->saveImage($publicFsPath);
            return '/' . $publicRel;
        }
    }

    private function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = StringHelper::slugify($name);
        $slug = $base;
        $i = 1;

        while (true) {
            $q = Events::query()->where('slug', $slug);
            if ($ignoreId) {
                $q->where('id', '!=', $ignoreId);
            }
            if (!$q->exists()) {
                return $slug;
            }
            $slug = $base . '-' . (++$i);
        }
    }

    /**
     * @return string[]
     */
    private function normalizeTags(string $raw): array
    {
        $raw = str_replace([';', "\n", "\r", "\t"], ',', $raw);
        $parts = array_filter(array_map('trim', explode(',', $raw)));
        $parts = array_values(array_unique($parts));

        return array_slice($parts, 0, 20);
    }

    /**
     * @param string[] $tags
     */
    private function syncTags(int $eventId, array $tags): void
    {
        $t = builder('event_tags');
        $t->where('event_id', $eventId)->delete();

        foreach ($tags as $tag) {
            builder('event_tags')->insert([
                'event_id' => $eventId,
                'tag' => $tag,
            ]);
        }
    }
}