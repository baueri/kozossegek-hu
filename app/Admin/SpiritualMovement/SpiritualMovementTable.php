<?php

namespace App\Admin\SpiritualMovement;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Helpers\SpiritualMovementHelper;
use App\QueryBuilders\SpiritualMovements;
use Framework\Http\Request;
use Framework\Model\PaginatedModelCollection;
use Framework\Support\Collection;

class SpiritualMovementTable extends AdminTable implements Editable, Deletable
{
    protected array $columns = [
        'name' => 'Név',
        'website' => 'Weboldal',
        'group_count' => 'közösségek'
    ];

    protected array $sortableColumns = [
        'name',
        'group_count'
    ];

    protected string $defaultOrderColumn = 'name';

    protected string $defaultOrder = 'asc';

    private SpiritualMovements $repository;

    public function __construct(Request $request, SpiritualMovements $repository)
    {
        parent::__construct($request);
        $this->repository = $repository;
    }

    protected function getData(): PaginatedModelCollection
    {
        $query = $this->repository
            ->orderBy(...$this->order);
        if ($name = $this->request['name']) {
            $query->where('name', 'like', "%{$name}%")
                ->orWhere('website', 'like', "%{$name}%");
        }

        $movements = $query->paginate($this->perpage);

        return SpiritualMovementHelper::loadGroupsCount($movements);
    }

    /**
     * @inheritDoc
     */
    public function getDeleteUrl($model): string
    {
        return route('admin.spiritual_movement.delete', $model);
    }

    /**
     * @inheritDoc
     */
    public function getEditUrl($model): string
    {
        return route('admin.spiritual_movement.edit', $model);
    }

    public function getEditColumn(): string
    {
        return 'name';
    }

    private function getGroupsCount(Collection $movements): array
    {
        if ($movements->isEmpty()) {
            return [];
        }
        $ids = $movements->pluck('id')->implode(',');
        return db()->select(
            "select count(*) as cnt, spiritual_movement_id
                    from church_groups
                    where spiritual_movement_id in ($ids) and deleted_at is null
                    group by spiritual_movement_id"
        );
    }
}
