<?php

declare(strict_types=1);

namespace App\Admin\Event;

use App\Admin\Components\AdminTable\PaginatedAdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Enums\EventLifeCycle;
use App\Enums\EventStatus;
use App\Models\Event;
use App\QueryBuilders\Events;
use Framework\Database\PaginatedResultSetInterface;

class EventTable extends PaginatedAdminTable implements Editable, Deletable
{
    protected array $columns = [
        'id' => '#',
        'name' => 'Cím',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'lifecycle' => '',
        'starts_at' => 'Kezdés',
        'created_at' => 'Létrehozva',
        'updated_at' => 'Utoljára módosítva',
        'delete' => '<i class="fa fa-trash-alt" title="Törlés"></i>',
    ];

    protected function bootPaginatedAdminTable(): void
    {
        $this->columns['lifecycle'] = lang('event_life_cycle.heading');
    }

    protected array $sortableColumns = [
        'id',
        'starts_at',
        'created_at',
        'updated_at',
    ];

    protected string $defaultOrder = 'desc';

    public function getSlug($slug, Event $event): string
    {
        return "<a href='{$event->getUrl()}' target='_blank'>{$slug}</a>";
    }

    public function getUserId(...$params): string
    {
        /** @var Event $event */
        [,$event] = $params;
        return $event->user->name ?? '<i style="color: #aaa">ismeretlen</i>';
    }

    protected function getData(): PaginatedResultSetInterface
    {
        $filter = $this->request->collect();

        $query = Events::query()
            ->when($filter['search'], fn ($query, $search) => $query->where('name', 'like', "%$search%"))
            ->when($filter['status'], fn (Events $query, string $status) => $query->where('status', $status))
            ->when($filter['lifecycle'], fn (Events $query, string $lifecycle) => $query->where('lifecycle', $lifecycle))
            ->when($filter['date_from'], fn (Events $query, string $from) => $query->where('starts_at', '>=', "{$from} 00:00:00"))
            ->when($filter['date_to'], fn (Events $query, string $to) => $query->where('starts_at', '<=', "{$to} 23:59:59"))
        ;

        $query->with('user');

        $query->orderBy(...$this->order);

        return $query->paginate();
    }

    public function getEditUrl($model): string
    {
        return route('admin.event.edit', $model);
    }

    public function getEditColumn(): string
    {
        return 'name';
    }

    public function getStatus(?string $status): string
    {
        $status = $status ?: '-';
        $translated = $status;
        if ($status !== '-') {
            try {
                $translated = EventStatus::from($status)->translate();
            } catch (\Throwable) {
                $translated = $status;
            }
        }

        $modifier = match ($status) {
            'approved' => 'approved',
            'pending' => 'pending',
            'rejected' => 'rejected',
            'draft' => 'draft',
            default => 'unknown',
        };

        $safe = htmlspecialchars((string) $translated, ENT_QUOTES, 'UTF-8');

        return "<span class='event-status-pill event-status-pill--{$modifier}'>{$safe}</span>";
    }

    public function getLifecycle(?string $lifecycle, Event $event): string
    {
        $lifecycle = $lifecycle ?: '-';
        $translated = $lifecycle;
        if ($lifecycle !== '-') {
            try {
                $translated = EventLifeCycle::from($lifecycle)->translate();
            } catch (\Throwable) {
                $translated = $lifecycle;
            }
        }

        if ($lifecycle === '-') {
            $safe = htmlspecialchars((string) $translated, ENT_QUOTES, 'UTF-8');
            return "<span class='event-lifecycle-pill event-lifecycle-pill--unknown'>{$safe}</span>";
        }

        $modifier = $lifecycle === 'cancelled' ? 'cancelled' : 'active';
        $toggleTitle = $lifecycle === 'cancelled'
            ? lang('event_life_cycle.toggle_activate')
            : lang('event_life_cycle.toggle_cancel');
        $toggleUrl = route('admin.event.toggle_lifecycle', $event);
        $safe = htmlspecialchars((string) $translated, ENT_QUOTES, 'UTF-8');
        $safeTitle = htmlspecialchars($toggleTitle, ENT_QUOTES, 'UTF-8');

        return "<a class='event-lifecycle-toggle' href='{$toggleUrl}' title='{$safeTitle}'><span class='event-lifecycle-pill event-lifecycle-pill--{$modifier}'>{$safe}</span></a>";
    }

    public function getDeleteUrl($model): string
    {
        return route('admin.event.delete', $model);
    }

    public function getDelete($value, Event $event): string
    {
        $url = $this->getDeleteUrl($event);
        return "<a href='{$url}' title='Végleges törlés' onclick=\"return confirm('Biztosan törlöd?');\"><i class='fa fa-trash-alt text-danger'></i></a>";
    }
}
