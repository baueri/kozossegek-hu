<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\QueryBuilders\Events;
use Carbon\Carbon;
use Framework\Database\Builder;
use Framework\Http\Controller;
use Framework\Http\Response;

class EventController extends Controller
{
    public function list()
    {
        $date = (string) $this->request->get('date');
        $search = trim((string) $this->request->get('search'));
        $date_from = (string) $this->request->get('date_from');
        $date_to = (string) $this->request->get('date_to');

        $eventTypeLabels = [
            'lelki' => 'Lelki program',
            'kozossegi' => 'Közösségi',
            'ifjusagi' => 'Ifjúsági',
            'csaladi' => 'Családi',
            'kulturalis' => 'Kulturális',
            'kepzes' => 'Képzés',
            'zarandoklat' => 'Zarándoklat',
        ];

        $typesParam = $this->request->get('types', []);
        $selectedTypes = match (true) {
            is_array($typesParam) => $typesParam,
            is_string($typesParam) => array_filter(array_map('trim', explode(',', $typesParam))),
            default => [],
        };
        $selectedTypes = array_values(array_intersect($selectedTypes, array_keys($eventTypeLabels)));

        $now = now();
        $rangeStart = null;
        $rangeEnd = null;

        if ($date && $date !== 'custom') {
            $today = Carbon::parse($now)->startOfDay();

            [$rangeStart, $rangeEnd] = match ($date) {
                'today' => [$today, Carbon::parse($today)->endOfDay()],
                'tomorrow' => [
                    Carbon::parse($today)->addDay(),
                    Carbon::parse($today)->addDay()->endOfDay(),
                ],
                'weekend' => (function () use ($today) {
                    $sat = Carbon::parse($today)->next(Carbon::SATURDAY)->startOfDay();
                    $sun = Carbon::parse($sat)->next(Carbon::SUNDAY)->endOfDay();
                    return [$sat, $sun];
                })(),
                '7days' => [$now, Carbon::parse($now)->addDays(7)->endOfDay()],
                '30days' => [$now, Carbon::parse($now)->addDays(30)->endOfDay()],
                default => [null, null],
            };
        }

        if ($date === 'custom') {
            $from = $date_from ? Carbon::parse($date_from)->startOfDay() : null;
            $to = $date_to ? Carbon::parse($date_to)->endOfDay() : null;

            $rangeStart = $from;
            $rangeEnd = $to;
        }

        $events = Events::query()
            ->with('tags')
            ->approved()
            ->active()
            ->upcoming()
            ->when($search, function (Events $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('location_name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->when($selectedTypes, fn (Events $query, array $types) => $query->whereTag($types))
            ->when($rangeStart && $rangeEnd, fn (Events $query) => $query->between($rangeStart, $rangeEnd))
            ->when($rangeStart && !$rangeEnd, fn (Events $query) => $query->where('starts_at', '>=', $rangeStart))
            ->when(!$rangeStart && $rangeEnd, fn (Events $query) => $query->where('starts_at', '<=', $rangeEnd))
            ->orderBy('starts_at')
            ->paginate()->castInto('toSearchResult');

        return view('portal.event.list', [
            'events' => $events,
            'total' => $events->total(),
            'date' => $date,
            'search' => $search,
            'eventTypeLabels' => $eventTypeLabels,
            'selectedTypes' => $selectedTypes,
            'date_from' => $date_from,
            'date_to' => $date_to,
        ]);
    }

    public function show(Events $events)
    {
        $date = $this->request->getUriValue('date');
        $slug = $this->request->getUriValue('slug');

        $event = $events->bySlug($slug)->approved()->firstOrFail();

        return view('portal/event/show.php', compact('event'));
    }

    public function ics()
    {
        $event = Events::query()->approved()->wherePK($this->request->getUriValue('event'))->firstOrFail();
        $start = $event->starts_at->format('Ymd\THis');
        $end = $event->ends_at->format('Ymd\THis');

        $ics = "BEGIN:VCALENDAR
            VERSION:2.0
            BEGIN:VEVENT
            SUMMARY:{$event->name}
            DTSTART:{$start}
            DTEND:{$end}
            DESCRIPTION:{$event->description}
            LOCATION:{$event->location_name}
            END:VEVENT
            END:VCALENDAR";

        
        header('Content-Type: text/calendar');
        header('Content-Disposition: attachment; filename="event.ics"');
        echo $ics;
    }
}
