<?php

namespace App\Admin\SpiritualMovement;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Models\SpiritualMovement;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\SpiritualMovements;
use Framework\Http\Request;
use Framework\Model\PaginatedModelCollection;

class SpiritualMovementTable extends AdminTable implements Editable, Deletable
{
    protected array $columns = [
        'name' => 'Név',
        'website' => 'Weboldal',
        'groups_count' => 'közösségek'
    ];

    protected array $sortableColumns = [
        'name',
        'groups_count'
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
            ->orderBy(...$this->order)
            ->withCount('groups', fn (ChurchGroups $query) => $query->active());
        if ($name = $this->request['name']) {
            $query->where('name', 'like', "%{$name}%")
                ->orWhere('website', 'like', "%{$name}%");
        }

        return $query->paginate($this->perpage);
    }

    public function getGroupsCount($count, SpiritualMovement $movement): string
    {
        return $this->getLink(route('admin.group.list', ['spiritual_movement_id' => $movement->getId()]), $count);
    }

    public function getDeleteUrl($model): string
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
