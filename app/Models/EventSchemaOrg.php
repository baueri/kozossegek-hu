<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;

/**
 * schema.org Event as array for JSON-LD (Google event structured data).
 */
final class EventSchemaOrg
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(Event $event): array
    {
        /** @var Carbon $start */
        $start = $event->starts_at;
        $end = $event->ends_at;

        if ($event->all_day) {
            $startDate = $start->format('Y-m-d');
            if ($end instanceof Carbon && ! $start->isSameDay($end)) {
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
            'name' => $event->name,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'eventStatus' => $event->isCancelled()
                ? 'https://schema.org/EventCancelled'
                : 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'url' => $event->getUrl(),
        ];

        $description = trim(strip_tags((string) $event->description));
        if ($description !== '') {
            $data['description'] = mb_strlen($description) > 5000
                ? mb_substr($description, 0, 5000)
                : $description;
        }

        $img = trim((string) $event->getFeaturedImageUrl());
        if ($img !== '') {
            $data['image'] = str_starts_with($img, 'http')
                ? $img
                : rtrim(get_site_url(), '/') . '/' . ltrim($img, '/');
        }

        $location = self::location($event);
        if ($location !== null) {
            $data['location'] = $location;
        }

        $organizer = trim((string) $event->organizer);
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
    private static function location(Event $event): ?array
    {
        $name = trim((string) $event->location_name);
        $addressLine = trim((string) $event->address);

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

        $lat = $event->lat;
        $lng = $event->lng;
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
