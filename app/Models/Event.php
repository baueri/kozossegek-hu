<?php

declare(strict_types=1);

namespace App\Models;

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
 * @property int $institute_id
 * @property null|Institute $institue
 * @property int $user_id
 * @property User $user
 * @property string $location_name
 * @property string $address
 * @property string $lat
 * @property string $lng
 * @property string $featured_image
 * @property Collection $tags
 */
class Event extends Entity
{
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
}
