<?php

declare(strict_types=1);

namespace App\Portal\Services;

use App\QueryBuilders\Events;
use App\Helpers\PathHelper;
use App\Storage\Base64Image;
use Carbon\Carbon;
use Framework\Http\Request;
use Framework\Support\StringHelper;
use InvalidArgumentException;

class UserEventFormHandler
{
    /**
     * @return array<string, mixed>
     */
    public function validatedPayload(Request $request, bool $forCreate): array
    {
        $data = $request->only(
            'name',
            'description',
            'starts_at',
            'ends_at',
            'all_day',
            'organizer',
            'location_name',
            'address',
            'lat',
            'lng',
            'lifecycle',
        );

        $name = trim((string) ($data['name'] ?? ''));
        if ($name === '') {
            throw new InvalidArgumentException('Az esemény címe kötelező.');
        }

        $allDay = (bool) ($data['all_day'] ?? false);

        $startsRaw = trim((string) ($data['starts_at'] ?? ''));
        if ($startsRaw === '') {
            throw new InvalidArgumentException('A kezdés időpontja kötelező.');
        }
        try {
            $starts = Carbon::parse($startsRaw);
        } catch (\Throwable) {
            throw new InvalidArgumentException('Érvénytelen kezdés időpont.');
        }
        if ($allDay) {
            $starts = $starts->copy()->startOfDay();
        }

        $ends = null;
        $endsRaw = trim((string) ($data['ends_at'] ?? ''));
        if ($endsRaw !== '') {
            try {
                $ends = Carbon::parse($endsRaw);
            } catch (\Throwable) {
                throw new InvalidArgumentException('Érvénytelen befejezés időpont.');
            }
            if ($allDay) {
                $ends = $ends->copy()->startOfDay();
            }
            if ($ends->lt($starts)) {
                throw new InvalidArgumentException('A befejezés nem lehet a kezdés előtt.');
            }
        }

        $lifecycle = 'active';
        if (!$forCreate) {
            $lifecycle = (string) ($data['lifecycle'] ?? 'active');
            if (!in_array($lifecycle, ['active', 'cancelled'], true)) {
                $lifecycle = 'active';
            }
        }

        return [
            'name' => $name,
            'description' => (string) ($data['description'] ?? ''),
            'starts_at' => $starts->format('Y-m-d H:i:s'),
            'ends_at' => $ends?->format('Y-m-d H:i:s'),
            'all_day' => (int) $allDay,
            'organizer' => trim((string) ($data['organizer'] ?? '')),
            'location_name' => trim((string) ($data['location_name'] ?? '')),
            'address' => trim((string) ($data['address'] ?? '')),
            'lat' => $this->nullableDecimal($data['lat'] ?? null),
            'lng' => $this->nullableDecimal($data['lng'] ?? null),
            'lifecycle' => $lifecycle,
        ];
    }

    public function persistFeaturedImage(string $base64): ?string
    {
        $base64 = trim($base64);
        if ($base64 === '') {
            return null;
        }

        $image = new Base64Image($base64);
        $hash = substr(hash('SHA256', $base64), 0, 16);
        $loc = PathHelper::eventFeaturedImageLocation($hash);
        $image->saveImage($loc['fs']);

        return $loc['url'];
    }

    public function makeUniqueSlug(string $name, ?int $ignoreId = null): string
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
    public function normalizeTags(string $raw): array
    {
        $raw = str_replace([';', "\n", "\r", "\t"], ',', $raw);
        $parts = array_filter(array_map('trim', explode(',', $raw)));
        $parts = array_values(array_unique($parts));

        return array_slice($parts, 0, 20);
    }

    /**
     * @param string[] $tags
     */
    public function syncTags(int $eventId, array $tags): void
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

    private function nullableDecimal(mixed $v): ?string
    {
        $v = trim((string) ($v ?? ''));

        return $v === '' ? null : $v;
    }
}
