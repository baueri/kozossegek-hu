<?php

namespace App\Admin\Group;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Models\GroupStatus;
use App\Models\GroupView;
use App\Repositories\GroupViews;
use App\Helpers\GroupHelper;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use Framework\Support\StringHelper;

class GroupTable extends AdminTable implements Editable, Deletable
{

    protected $columns = [
        'id' => '#',
        'image' => '<i class="fa fa-image" title="Fotó"></i>',
        'view' => '<i class="fa fa-eye" title="Megtekintés a honlapon"></i>',
        'name' => 'Közösség neve',
        'city' => 'Település',
        'institute_name' => 'Intézmény / plébánia',
        'group_leaders' => 'Közösség vezető(i)',
        'age_group' => 'Korosztály',
        'status' => '<i class="fa fa-check-circle" title="Aktív"></i>',
        'pending' => '<i class="fa fa-thumbs-up" title="Jóváhagyva"></i>',
        'has_document' => '<i class="fa fa-file-word" title="Van feltöltött intézményvezetői igazolása"></i>',
        'created_at' => 'Létrehozva',
    ];

    protected array $centeredColumns = ['status', 'pending', 'has_document', 'view'];
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

        return "<i class='$class'></i>";
    }

    public function getPending($pending)
    {
        if ($pending) {
            return static::getBanIcon('nem');
        }

        return self::getCheckIcon('igen');
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

    public function getImage($image, GroupView $group)
    {
        $imageUrl = $group->getFirstImage() . '?' . time();
        return "<img src='$imageUrl' style='max-width: 25px; height: auto;' title='<img src=\"$imageUrl\">' data-html='true'/>";
    }

    public function getHasDocument($document, GroupView $model)
    {
        if ($model->hasDocument()) {
            return self::getCheckIcon('igen');
        }

        return self::getBanIcon('nem');
    }

    public function getEditColumn(): string
    {
        return 'name';
    }

    public function getView($null, GroupView $model)
    {
        return "<a href='{$model->url()}' target='_blank' title='megtekintés'><i class='fa fa-eye'></i></a>";
    }
}
