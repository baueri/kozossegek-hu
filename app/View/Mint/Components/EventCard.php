<?php

declare(strict_types=1);

namespace App\View\Mint\Components;

use App\Models\EventScheduleFormatter;
use Baueri\Mint\Context;
use Baueri\Mint\Module\Module;
use Carbon\Carbon;

class EventCard extends Module
{
    public function render(Context $context): string
    {
        $event = $context->resolve('event');

        if (! is_array($event)) {
            throw new \InvalidArgumentException('mod-event-card requires :event array.');
        }

        $lifecycle_cancelled = ($event['lifecycle'] ?? '') === 'cancelled';
        $tags = $event['tags'] ?? [];
        $tags_preview = is_array($tags) && $tags !== [] ? array_slice($tags, 0, 3) : [];
        $all_day = (bool) ($event['all_day'] ?? false);
        $startsAt = $event['starts_at'] ?? null;
        $endsAt = $event['ends_at'] ?? null;
        $show_tags = $tags_preview !== [];

        $schedule_label = $event['schedule_card_label'] ?? null;
        if ($schedule_label === null && $startsAt instanceof Carbon) {
            $schedule_label = EventScheduleFormatter::formatCardScheduleRangeLabel(
                $startsAt,
                $endsAt instanceof Carbon ? $endsAt : null,
                $all_day
            );
        }
        $schedule_label = (string) ($schedule_label ?? '');

        return $this->view($context, 'components/event-card.php', compact(
            'event',
            'lifecycle_cancelled',
            'tags_preview',
            'show_tags',
            'schedule_label',
        ));
    }
}
