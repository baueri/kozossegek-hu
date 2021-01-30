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
        'pending' => '<i class="fa fa-thumbs-up" title="Jóváhagyva"></i>',
        'status' => '<i class="fa fa-check-circle" title="Aktív"></i>',
        'document' => '<i class="fa fa-file-word" title="Van feltöltött intézményvezetői igazolása"></i>',
        'city' => 'Település',
        'institute_name' => 'Intézmény / plébánia',
        'group_leaders' => 'Közösség vezető(i)',
        'age_group' => 'Korosztály',
        'created_at' => 'Létrehozva',
    ];

    protected array $centeredColumns = ['status', 'pending', 'document', 'view', 'image'];

    protected array $sortableColumns = ['id', 'status', 'pending', 'document', 'created_at'];

    private GroupViews $repository;

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
        $ageGroups = GroupHelper::getAgeGroups($ageGroup);

        return $ageGroups->map(fn($name, $key) =>
            $this->getLink(
                $this->getListUrl(['korosztaly' => $key]),
                $name,
                'szűrés erre'
            ))->implode(', ');
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

    public function getInstituteName($instituteName, GroupView $group)
    {
        return $this->getLink(
            $this->getListUrl(['institute_id' => $group->institute_id]),
            $instituteName,
            'szűrés erre az intézményre'
        );
    }

    public function getCity($cityName, $m)
    {
        return $this->getLink($this->getListUrl(['varos' => $cityName]), $cityName, 'keresés erre a városra');
    }

    public function getPending($pending, $group)
    {
        if ($pending == 1) {
            $icon = static::getIcon('fa fa-sync text-warning', 'megnyitás jóváhagyásra');
        } elseif ($pending == -1) {
            $icon = static::getBanIcon('jóváhagyás visszautasítva');
        } else {
            $icon = self::getCheckIcon('jóváhagyva');
        }

        return $this->getLink(route('admin.group.validate', $group), $icon);
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

    public function getDocument($document, GroupView $model)
    {
        if ($model->hasDocument()) {
            return self::getCheckIcon('van');
        }

        return self::getBanIcon('nincs');
    }

    public function getEditColumn(): string
    {
        return 'name';
    }

    public function getView($null, GroupView $model)
    {
        return "<a href='{$model->url()}' target='_blank' title='megtekintés'><i class='fa fa-eye'></i></a>";
    }

    private function getListUrl(array $params = [])
    {
        return route('admin.group.list', $params);
    }
}
