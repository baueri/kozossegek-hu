<?php

declare(strict_types=1);

namespace App\View\Mint\Components;

use Baueri\Mint\Context;
use Baueri\Mint\Module\Module;

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
        $show_all_day_end = $all_day
            && is_object($endsAt)
            && is_object($startsAt)
            && $endsAt->format('Y-m-d') !== $startsAt->format('Y-m-d');
        $show_time_end = ! $all_day && is_object($endsAt);
        $show_tags = $tags_preview !== [];

        return $this->view($context, 'components/event-card.php', compact(
            'event',
            'lifecycle_cancelled',
            'tags_preview',
            'show_tags',
            'all_day',
            'show_all_day_end',
            'show_time_end',
        ));
    }
}
