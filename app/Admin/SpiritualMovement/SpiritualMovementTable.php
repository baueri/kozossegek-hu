<?php

namespace App\Admin\SpiritualMovement;

use App\Admin\Components\AdminTable\PaginatedAdminTable;
use App\Admin\Components\AdminTable\Editable;
use App\Admin\Components\AdminTable\Traits\Destroyable;
use App\Enums\SpiritualMovementType;
use App\Models\SpiritualMovement;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\SpiritualMovements;
use Framework\Database\Builder;
use Framework\Http\Request;
use Framework\Model\PaginatedModelCollection;

class SpiritualMovementTable extends PaginatedAdminTable implements Editable
{
    use Destroyable;

    protected array $columns = [
        'name' => 'Név',
        'website' => 'Weboldal',
        'groups_count' => 'közösségek',
        'highlighted' => 'Kiemelt'
    ];

    protected array $sortableColumns = [
        'name',
        'highlighted'
    ];

    protected string $defaultOrderColumn = 'name';

    protected string $defaultOrder = 'asc';

    public readonly string $type;

    public function __construct(Request $request, private readonly SpiritualMovements $repository)
    {
        parent::__construct($request);

        $this->type = $request->get('type', SpiritualMovementType::spiritual_movement->name);
    }

    protected function getData(): PaginatedModelCollection
    {
        $query = $this->repository
            ->orderBy(...$this->order)
            ->withCount('groups', fn (ChurchGroups $query) => $query->active());

        if ($name = $this->request['name']) {
            $query->where(function (Builder $query) use ($name) {
                return $query->where('name', 'like', "%{$name}%")
                    ->orWhere('website', 'like', "%{$name}%");
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        return $query->paginate($this->perpage);
    }

    public function getGroupsCount($count, SpiritualMovement $movement): string
    {
        return $this->getLink(route('admin.group.list', ['spiritual_movement_id' => $movement->getId()]), $count);
    }

    public function getHighlighted($highlighted): string
    {
        return $highlighted ? static::getCheckIcon() : '';
    }

    public function getDestroyLink($model): string
    {
        return route('admin.spiritual_movement.delete', $model);
    }

    public function getEditUrl($model): string
    {
        return route('admin.spiritual_movement.edit', $model);
    }

    public function getEditColumn(): string
    {
        return 'name';
    }
}
