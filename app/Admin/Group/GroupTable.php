<?php

namespace App\Admin\Group;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Models\AgeGroup;
use App\Models\Group;
use App\Models\GroupStatus;
use App\Models\Institute;
use App\Repositories\GroupViews;

use App\Helpers\GroupHelper;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use Framework\Support\StringHelper;

class GroupTable extends AdminTable implements Editable, Deletable
{

    protected $columns = [
        'id' => '#',
        'name' => 'Közösség neve',
        'city' => 'Település',
        'institute_name' => 'Intézmény / plébánia',
        'group_leaders' => 'Közösség vezető(i)',
        'age_group' => 'Korosztály',
        'status' => 'Státusz',
        'pending' => 'Függőben',
        'created_at' => 'Létrehozva',
    ];

    protected $centeredColumns = ['status'];
    /**
     * @var GroupViews
     */
    private $repository;

    /**
     * GroupTable constructor.
     * @param Request $request
     * @param GroupViews $repository
     */
    public function __construct(Request $request, GroupViews $repository)
    {
        parent::__construct($request);
        $this->repository = $repository;
    }

    public function getAgeGroup($ageGroup)
    {
        return GroupHelper::parseAgeGroup($ageGroup);
    }

    public function getCreatedAt($createdAt)
    {
        return date('Y.m.d H:i', strtotime($createdAt));
    }

    public function getStatus($status)
    {
        $status = new GroupStatus($status);
        $class = $status->getClass();
        $text = $status->translate();
        return "<i class='$class' title='$text'></i>";
    }

    public function getPending($pending)
    {
        if ($pending) {
            return 'igen';
        }
        
        return 'nem';
    }
    
    public function getGroupLeaders($groupLeaders)
    {
        $shorten = StringHelper::shorten($groupLeaders, 15, '...');
        return "<span title='$groupLeaders'>$shorten</span>";
    }

    /**
     * @return PaginatedResultSetInterface
     */
    protected function getData(): PaginatedResultSetInterface
    {
        return $this->repository->search($this->request->merge([
            'order_by' => 'id',
            'order' => 'desc',
            'deleted' => $this->request->route->getAs() == 'admin.group.trash'
        ]));
    }

    public function getDeleteUrl($model): string
    {
        return route('admin.group.delete', $model);
    }

    public function getEditUrl($model): string
    {
        return route('admin.group.edit', $model);
    }

    public function getEditColumn(): string
    {
        return 'name';
    }
}
