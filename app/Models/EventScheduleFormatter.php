<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;

/**
 * Hungarian locale schedule strings for events (detail lists, cards, JSON consumers via Event).
 */
final class EventScheduleFormatter
{
    /**
     * Human-readable start–end line for the portal (same calendar day vs multi-day, all-day vs timed).
     * Uses Hungarian locale: abbreviated months (MMM), compact weekday initials (H, K, Sze, Cs, P, Szo, V), 24-hour clock, en dash between parts.
     */
    public static function formatScheduleRangeLabel(Carbon $start, ?Carbon $end, bool $allDay): string
    {
        $start = $start->copy()->locale('hu');
        $end = $end?->copy()->locale('hu');

        if ($allDay) {
            if (! $end instanceof Carbon || $start->isSameDay($end)) {
                return self::humanDateHungarian($start);
            }

            return self::humanAllDayRangeHungarian($start, $end);
        }

        $dateLine = self::humanDateHungarian($start);
        $startTime = $start->format('H:i');

        if (! $end instanceof Carbon) {
            return $dateLine . ' ' . $startTime;
        }

        if ($start->isSameDay($end)) {
            return $dateLine . ' ' . $startTime . ' – ' . $end->format('H:i');
        }

        return $dateLine . ' ' . $startTime
            . ' – ' . self::humanDateHungarian($end) . ' ' . $end->format('H:i');
    }

    /**
     * Schedule line for Mint event cards: start weekday first, only that weekday (no weekday on end dates).
     */
    public static function formatCardScheduleRangeLabel(Carbon $start, ?Carbon $end, bool $allDay): string
    {
        $start = $start->copy()->locale('hu');
        $end = $end?->copy()->locale('hu');

        if ($allDay) {
            if (! $end instanceof Carbon || $start->isSameDay($end)) {
                return self::humanDateHungarianStartFirst($start);
            }

            return self::humanAllDayRangeHungarianCard($start, $end);
        }

        $startLine = self::humanDateHungarianStartFirst($start);
        $startTime = $start->format('H:i');

        if (! $end instanceof Carbon) {
            return $startLine . ' ' . $startTime;
        }

        if ($start->isSameDay($end)) {
            return $startLine . ' ' . $startTime . ' – ' . $end->format('H:i');
        }

        return $startLine . ' ' . $startTime
            . ' – ' . self::humanDateHungarian($end, false) . ' ' . $end->format('H:i');
    }

    /**
     * One calendar day in Hungarian (abbreviated month, day, compact weekday; year only when not the current year).
     */
    private static function humanDateHungarian(Carbon $d, bool $includeWeekday = true): string
    {
        $d = $d->copy()->locale('hu');
        $showYear = $d->year !== now()->year;

        $datePart = $showYear
            ? $d->isoFormat('YYYY. MMM D.')
            : $d->isoFormat('MMM D.');

        if (! $includeWeekday) {
            return $datePart;
        }

        return $datePart . ', ' . self::weekdayShortHungarian($d);
    }

    /**
     * Short weekday label (ISO weekday: 1 = Monday … 7 = Sunday).
     */
    private static function weekdayShortHungarian(Carbon $d): string
    {
        return match ($d->isoWeekday()) {
            1 => 'H',
            2 => 'K',
            3 => 'Sze',
            4 => 'Cs',
            5 => 'P',
            6 => 'Szo',
            7 => 'V',
            default => $d->isoFormat('ddd'),
        };
    }

    /**
     * All-day range: compact month when start/end share month and year, otherwise abbreviated dates.
     */
    private static function humanAllDayRangeHungarian(Carbon $start, Carbon $end): string
    {
        $start = $start->copy()->locale('hu');
        $end = $end->copy()->locale('hu');
        $ref = now();
        $showYear = $start->year !== $ref->year || $end->year !== $ref->year;

        if ($start->isSameMonth($end) && $start->year === $end->year) {
            $month = $start->isoFormat('MMM');
            $yearPrefix = $showYear ? $start->format('Y. ') : '';

            return $yearPrefix . $month . ' ' . $start->format('j') . '–' . $end->format('j') . '.';
        }

        return self::humanDateHungarian($start) . ' – ' . self::humanDateHungarian($end, false);
    }

    /**
     * Abbreviated date with weekday at the beginning (start of event only).
     */
    private static function humanDateHungarianStartFirst(Carbon $d): string
    {
        $d = $d->copy()->locale('hu');
        $showYear = $d->year !== now()->year;
        $datePart = $showYear
            ? $d->isoFormat('YYYY. MMM D.')
            : $d->isoFormat('MMM D.');

        return self::weekdayShortHungarian($d) . ', ' . $datePart;
    }

    /**
     * All-day range for cards: weekday only for the start day.
     */
    private static function humanAllDayRangeHungarianCard(Carbon $start, Carbon $end): string
    {
        $start = $start->copy()->locale('hu');
        $end = $end->copy()->locale('hu');
        $ref = now();
        $showYear = $start->year !== $ref->year || $end->year !== $ref->year;

        if ($start->isSameMonth($end) && $start->year === $end->year) {
            $month = $start->isoFormat('MMM');
            $yearPrefix = $showYear ? $start->format('Y. ') : '';

            return self::weekdayShortHungarian($start) . ', ' . $yearPrefix . $month . ' ' . $start->format('j') . '–' . $end->format('j') . '.';
        }

        return self::weekdayShortHungarian($start) . ', ' . self::humanDateHungarian($start, false) . ' – ' . self::humanDateHungarian($end, false);
    }
}
