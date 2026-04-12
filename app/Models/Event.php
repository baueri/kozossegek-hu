<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\SystemAdministration\SiteMap\ChangeFreq;
use App\Services\SystemAdministration\SiteMap\EntitySiteMappable;
use Carbon\Carbon;
use DateTimeInterface;
use Framework\Model\Entity;
use Framework\Support\Collection;

/**
 * @property string $name
 * @property string $description
 * @property bool $all_day
 * @property string $status
 * @property string $lifecycle
 * @property string $organizer 
 * @property DateTimeInterface $starts_at
 * @property ?DateTimeInterface $ends_at
 * @property string $slug
 * @property null|Institute $institue
 * @property int $user_id
 * @property User $user
 * @property string $location_name
 * @property string $address
 * @property string $lat
 * @property string $lng
 * @property string $featured_image
 * @property null|string $updated_at
 * @property Collection $tags
 */
class Event extends Entity
{
    use EntitySiteMappable;

    protected static string $primaryCol = 'id';

    protected array $casts = [
        'starts_at' => Carbon::class,
        'ends_at' => Carbon::class,
        'all_day' => 'bool',
    ];

    public function isCancelled(): bool
    {
        return $this->lifecycle === 'cancelled';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isOwnedBy(User $user): bool
    {
        return (int) $this->user_id === (int) $user->getId();
    }

    public function getUrl(): string
    {
        return route('event.show', ['date' => $this->starts_at->format('Y-m-d'), 'slug' => $this->slug]);
    }

    public function changeFreq(): ChangeFreq
    {
        return ChangeFreq::weekly;
    }

    public function priority(): ?string
    {
        return '0.7';
    }

    public function getFeaturedImageUrl(): string
    {
        return preg_replace('/^\/app/', '', $this->featured_image);
    }

    /**
     * Human-readable start–end line for the portal (same calendar day vs multi-day, all-day vs timed).
     */
    public function getScheduleRangeLabel(): string
    {
        /** @var Carbon $start */
        $start = $this->starts_at;
        $end = $this->ends_at;

        if ($this->all_day) {
            $startLabel = $start->format('Y. m. d.');
            if (!$end instanceof Carbon || $start->isSameDay($end)) {
                return $startLabel;
            }

            return $startLabel . ' - ' . $end->format('Y. m. d.');
        }

        $startLabel = $start->format('Y. m. d. H:i');
        if (!$end instanceof Carbon) {
            return $startLabel;
        }

        if ($start->isSameDay($end)) {
            return $startLabel . ' - ' . $end->format('H:i');
        }

        return $startLabel . ' - ' . $end->format('Y. m. d. H:i');
    }

    public function toSearchResult(): array
    {
        $tags = collect($this->tags ?? [])->pluck('tag')->all();

        return array_merge(
            [
                'url' => $this->getUrl(),
                'featured_image' => $this->getFeaturedImageUrl(),
                'tags' => $tags,
            ],
            $this->only([
                'id',
                'name',
                'description',
                'starts_at',
                'ends_at',
                'lifecycle',
                'address',
                'location_name',
                'all_day'
            ])
        );
    }

    /**
     * schema.org Event as array for JSON-LD (Google event structured data).
     *
     * @return array<string, mixed>
     */
    public function getSchemaOrgEvent(): array
    {
        /** @var Carbon $start */
        $start = $this->starts_at;
        $end = $this->ends_at;

        if ($this->all_day) {
            $startDate = $start->format('Y-m-d');
            if ($end instanceof Carbon && !$start->isSameDay($end)) {
                $endDate = $end->copy()->addDay()->format('Y-m-d');
            } else {
                $endDate = $start->copy()->addDay()->format('Y-m-d');
            }
        } else {
            $startDate = $start->toIso8601String();
            $endDate = $end instanceof Carbon
                ? $end->toIso8601String()
                : $start->copy()->addHour()->toIso8601String();
        }

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $this->name,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'eventStatus' => $this->isCancelled()
                ? 'https://schema.org/EventCancelled'
                : 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'url' => $this->getUrl(),
        ];

        $description = trim(strip_tags((string) $this->description));
        if ($description !== '') {
            $data['description'] = mb_strlen($description) > 5000
                ? mb_substr($description, 0, 5000)
                : $description;
        }

        $img = trim((string) $this->getFeaturedImageUrl());
        if ($img !== '') {
            $data['image'] = str_starts_with($img, 'http')
                ? $img
                : rtrim(get_site_url(), '/') . '/' . ltrim($img, '/');
        }

        $location = $this->schemaOrgLocation();
        if ($location !== null) {
            $data['location'] = $location;
        }

        $organizer = trim((string) $this->organizer);
        if ($organizer !== '') {
            $data['organizer'] = [
                '@type' => 'Organization',
                'name' => $organizer,
            ];
        }

        return $data;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function schemaOrgLocation(): ?array
    {
        $name = trim((string) $this->location_name);
        $addressLine = trim((string) $this->address);

        if ($name === '' && $addressLine === '') {
            return null;
        }

        $place = ['@type' => 'Place'];

        if ($name !== '') {
            $place['name'] = $name;
        }

        if ($addressLine !== '') {
            $place['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $addressLine,
            ];
        }

        $lat = $this->lat;
        $lng = $this->lng;
        if ($lat !== null && $lat !== '' && $lng !== null && $lng !== '') {
            $place['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $lat,
                'longitude' => (float) $lng,
            ];
        }

        return $place;
    }
}
