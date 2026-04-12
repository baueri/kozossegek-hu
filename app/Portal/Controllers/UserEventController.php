<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Models\Event;
use App\Models\User;
use App\Portal\Services\UserEventFormHandler;
use App\QueryBuilders\Events;
use App\Events\UserEventPendingModeration;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapQuery;
use Framework\Event\EventDisptatcher;
use Framework\Http\Message;
use Framework\Http\Request;
use InvalidArgumentException;
use Throwable;

class UserEventController extends PortalController
{
    public function index(): string
    {
        $user = Auth::user();
        $items = Events::query()
            ->where('user_id', $user->getId())
            ->orderBy('starts_at', 'desc')
            ->get()
            ->all();

        return view('portal.user_event.list', compact('items'));
    }

    public function createForm(): string
    {
        $event = new Event();
        $action = route('portal.my_event.store');
        $tags = '';

        return view('portal.user_event.form', compact('event', 'action', 'tags'));
    }

    public function store(Request $request, UserEventFormHandler $handler): never
    {
        $user = Auth::user();

        try {
            $payload = $handler->validatedPayload($request, true);
            $payload['slug'] = $handler->makeUniqueSlug($payload['name']);
            $payload['user_id'] = $user->getId();
            $payload['status'] = $user->isAdmin() ? 'approved' : 'pending';

            if ($path = $handler->persistFeaturedImage((string) $request->get('featured_image_data', ''))) {
                $payload['featured_image'] = $path;
            }

            $tags = $handler->normalizeTags((string) ($request->get('tags') ?? ''));

            /** @var Event $event */
            $event = Events::query()->create($payload);
            $handler->syncTags((int) $event->id, $tags);

            if ($payload['status'] === 'pending') {
                EventDisptatcher::dispatch(new UserEventPendingModeration($event));
            }

            Message::success($payload['status'] === 'pending'
                ? 'Az eseményed elmentve. Jóváhagyás után jelenik meg a portálon.'
                : 'Az esemény sikeresen létrejött.');
            redirect_route('portal.my_event.edit', ['id' => $event->id]);
        } catch (InvalidArgumentException $e) {
            Message::danger($e->getMessage());
            redirect_route('portal.my_event.create');
        } catch (Throwable $e) {
            report($e);
            Message::danger('Mentés sikertelen. Próbáld újra később.');
            redirect_route('portal.my_event.create');
        }
    }

    public function editForm(Request $request): string
    {
        $event = $this->findOwnedEventOrAbort((int) $request['id'], Auth::user());
        $action = route('portal.my_event.update', ['id' => $event->id]);
        $tagRows = builder('event_tags')->where('event_id', $event->id)->pluck('tag');
        $tags = implode(', ', array_filter($tagRows));

        return view('portal.user_event.form', compact('event', 'action', 'tags'));
    }

    public function update(Request $request, UserEventFormHandler $handler): never
    {
        $user = Auth::user();
        $event = $this->findOwnedEventOrAbort((int) $request['id'], $user);

        try {
            $payload = $handler->validatedPayload($request, false);
            $payload['slug'] = $event->slug;
            $payload['status'] = $event->status;
            $payload['user_id'] = $event->user_id;

            if ($path = $handler->persistFeaturedImage((string) $request->get('featured_image_data', ''))) {
                $payload['featured_image'] = $path;
            } else {
                $payload['featured_image'] = $event->featured_image;
            }

            $tags = $handler->normalizeTags((string) ($request->get('tags') ?? ''));

            Events::query()->save($event, $payload);
            $handler->syncTags((int) $event->id, $tags);

            Message::success('Az esemény frissítve.');
            redirect_route('portal.my_event.edit', ['id' => $event->id]);
        } catch (InvalidArgumentException $e) {
            Message::danger($e->getMessage());
            redirect_route('portal.my_event.edit', ['id' => $event->id]);
        } catch (Throwable $e) {
            report($e);
            Message::danger('Mentés sikertelen. Próbáld újra később.');
            redirect_route('portal.my_event.edit', ['id' => $event->id]);
        }
    }

    public function delete(Request $request): never
    {
        $event = $this->findOwnedEventOrAbort((int) $request['id'], Auth::user());
        Events::query()->hardDeleteModel($event);

        Message::warning('Esemény törölve.');
        redirect_route('portal.my_events');
    }

    /**
     * @return array{success: bool, results?: list<array<string, mixed>>, msg?: string}
     */
    public function geocodeSearch(Request $request, OpenStreetMapQuery $osm): array
    {
        $q = trim((string) $request->get('q', ''));
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

    private function findOwnedEventOrAbort(int $id, User $user): Event
    {
        $event = Events::query()->wherePK($id)->first();
        if (!$event || !$event->isOwnedBy($user)) {
            raise_403();
        }

        return $event;
    }

    /**
     * Hungarian-style single line: "{postcode} {city}, {road} {house_number}"
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
}
