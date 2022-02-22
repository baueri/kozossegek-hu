<?php

namespace App\Admin\Group;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Models\ChurchGroupView;
use App\Models\GroupStatus;
use App\Services\GroupSearchRepository;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use Framework\Support\StringHelper;

class GroupTable extends AdminTable implements Editable, Deletable
{
    protected array $columns = [
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

    protected array $columnClasses = ['age_group' => 'd-none d-xl-table-cell'];

    private GroupSearchRepository $repository;

    public function __construct(Request $request, GroupSearchRepository $repository)
    {
        parent::__construct($request);
        $this->repository = $repository;
    }

    public function getAgeGroup($ageGroup, ChurchGroupView $churchGroup): string
    {
        return $churchGroup->ageGroup();
    }

    public function getCreatedAt($createdAt): string
    {
        $fullDate = date('Y.m.d H:i', strtotime($createdAt));
        $date = date('Y.m.d', strtotime($createdAt));
        return "<span title='{$fullDate}'>{$date}</span>";
    }

    public function getStatus($status): string
    {
        $status = new GroupStatus($status);
        $class = $status->getClass();

        return "<i class='$class'></i>";
    }

    public function getInstituteName($instituteName, ChurchGroupView $group): string
    {
        return $this->getLink(
            $this->getListUrl(['institute_id' => $group->institute_id]),
            static::excerpt($instituteName, true, 45),
            'szűrés erre az intézményre'
        );
    }

    public function getCity($cityName): string
    {
        return $cityName;
    }

    public function getPending($pending, $group): string
    {
        if ($pending == 1) {
            $icon = static::getIcon('fa fa-sync text-warning', 'jóváhagyásra vár');
        } elseif ($pending == -1) {
            $icon = static::getBanIcon('jóváhagyás visszautasítva');
        } else {
            $icon = self::getCheckIcon('jóváhagyva');
        }

        return $this->getLink(route('admin.group.validate', $group), $icon);
    }

    public function getGroupLeaders($groupLeaders): string
    {
        $shorten = StringHelper::shorten($groupLeaders, 15, '...');
        return "<span title='$groupLeaders'>$shorten</span>";
    }

    protected function getData(): PaginatedResultSetInterface
    {
        return $this->repository->search($this->request->merge([
            'deleted' => $this->request->route->getAs() == 'admin.group.trash'
        ]), $this->perpage);
    }

    public function getDeleteUrl($model): string
    {
        return route('admin.group.delete', $model);
    }

    public function getName($name, $model)
    {
        return $this->getEdit($name, $model, 25);
    }

    public function getEditUrl($model): string
    {
        return route('admin.group.edit', $model);
    }

    public function getImage($image, ChurchGroupView $group): string
    {
        $imageUrl = $group->getThumbnail() . '?' . time();
        return "<img src='$imageUrl' style='max-width: 25px; height: auto;' title='<img src=\"$imageUrl\">' data-html='true'/>";
    }

    public function getDocument($document, ChurchGroupView $model): string
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

    public function getView($null, ChurchGroupView $model): string
    {
        return "<a href='{$model->url()}' target='_blank' title='megtekintés'><i class='fa fa-eye'></i></a>";
    }

    private function getListUrl(array $params = []): string
    {
        return route('admin.group.list', $params);
    }
}
