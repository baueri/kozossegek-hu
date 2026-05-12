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
 * @property null|string $featured_image_poster
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
        return preg_replace('/^\/app/', '', (string) $this->featured_image);
    }

    /**
     * Larger image for modal / OG / JSON-LD when present.
     */
    public function getFeaturedImagePosterUrl(): ?string
    {
        $p = $this->featured_image_poster ?? null;
        if ($p === null || $p === '') {
            return null;
        }

        return preg_replace('/^\/app/', '', (string) $p);
    }

    /**
     * Best image URL for social / schema (poster preferred).
     */
    public function getFeaturedImageHeroUrl(): string
    {
        return $this->getFeaturedImagePosterUrl() ?? $this->getFeaturedImageUrl();
    }

    /**
     * Human-readable start–end line for the portal (same calendar day vs multi-day, all-day vs timed).
     */
    public function getScheduleRangeLabel(): string
    {
        /** @var Carbon $start */
        $start = $this->starts_at;
        /** @var Carbon|null $end */
        $end = $this->ends_at instanceof Carbon ? $this->ends_at : null;

        return EventScheduleFormatter::formatScheduleRangeLabel($start, $end, $this->all_day);
    }

    public function getCardScheduleLabel(): string
    {
        /** @var Carbon $start */
        $start = $this->starts_at;
        /** @var Carbon|null $end */
        $end = $this->ends_at instanceof Carbon ? $this->ends_at : null;

        return EventScheduleFormatter::formatCardScheduleRangeLabel($start, $end, $this->all_day);
    }

    /**
     * Short venue or address line for the event page hero (chip next to the date).
     */
    public function getHeroLocationLabel(): ?string
    {
        $name = trim((string) ($this->location_name ?? ''));
        if ($name !== '') {
            return self::truncateHeroLabel($name);
        }

        $addr = trim((string) ($this->address ?? ''));
        if ($addr !== '') {
            return self::truncateHeroLabel($addr);
        }

        return null;
    }

    private static function truncateHeroLabel(string $value): string
    {
        if (mb_strlen($value) <= 52) {
            return $value;
        }

        return rtrim(mb_substr($value, 0, 51)) . '…';
    }

    public function toSearchResult(): array
    {
        $tags = collect($this->tags ?? [])->pluck('tag')->all();

        return array_merge(
            [
                'url' => $this->getUrl(),
                'featured_image' => $this->getFeaturedImageUrl(),
                'featured_image_poster' => $this->getFeaturedImagePosterUrl(),
                'tags' => $tags,
                'schedule_range_label' => $this->getScheduleRangeLabel(),
                'schedule_card_label' => $this->getCardScheduleLabel(),
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
        return EventSchemaOrg::toArray($this);
    }
}

