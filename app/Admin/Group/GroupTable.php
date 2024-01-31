<?php

namespace App\Admin\Group;

use App\Admin\Components\AdminTable\PaginatedAdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Admin\Components\AdminTable\Traits\Destroyable;
use App\Admin\Components\AdminTable\Traits\SoftDeletable;
use App\Enums\GroupStatus;
use App\Models\ChurchGroupView;
use App\Services\GroupSearchRepository;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use Framework\Model\Entity;
use Framework\Support\StringHelper;

class GroupTable extends PaginatedAdminTable implements Editable
{
    use Destroyable;
    use SoftDeletable;

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
        'confirmed_at' => 'Megerősítve',
    ];

    protected array $centeredColumns = ['status', 'pending', 'document', 'view', 'image', 'created_at', 'confirmed_at'];

    protected array $sortableColumns = ['id', 'status', 'pending', 'document', 'created_at', 'confirmed_at'];

    protected array $columnClasses = ['age_group' => 'd-none d-xl-table-cell'];

    protected string $emptyTrashRoute = 'admin.group.empty_trash';

    public function __construct(Request $request, private GroupSearchRepository $repository)
    {
        $this->trashView = $request->route->getAs() == 'admin.group.trash';
        parent::__construct($request);
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

    public function getConfirmedAt($confirmedAt, ChurchGroupView $groupView): string
    {
        if (!$confirmedAt) {
            return '-';
        }
        $fullDate = date('Y.m.d H:i', strtotime($confirmedAt));
        $date = date('Y.m.d', strtotime($confirmedAt));

        return "<span title='{$fullDate}'>{$date}</span>";
    }

    public function getStatus($status): string
    {
        $status = GroupStatus::from($status);

        return "<i class='{$status->class()} title='{$status->translate()}'></i>";
    }

    public function getInstituteName($instituteName, ChurchGroupView $group): string
    {
        return $this->getLink(
            $this->getListUrl(['institute_id' => $group->institute_id]),
            static::excerpt((string) $instituteName, true, 45),
            'szűrés erre az intézményre'
        );
    }

    public function getCity($cityName, Entity $group): string
    {
        if ($cityName) {
            return $cityName;
        }
        report("ennek a közösségnek nincs városa: {$group->getId()}");
        return '';
    }

    public function getPending($pending, $group): string
    {
        if ($pending == 1) {
            $icon = static::getIcon('fa fa-sync text-warning', 'jóváhagyásra vár');
        } elseif ($pending == -1) {
            $icon = static::getBanIcon('jóváhagyás visszautasítva');
        } else {
            $icon = static::getCheckIcon('jóváhagyva');
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
            'deleted' => $this->trashView
        ]), $this->perpage);
    }

    public function getSoftDeleteLink($model): string
    {
        return route('admin.group.delete', $model);
    }

    public function getName($name, $model): string
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
        return "<img src='$imageUrl' style='max-width: 25px; height: auto;' title='<img src=\"$imageUrl\">' data-html='true' alt='{$group->institute_name}'/>";
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

    public function getDestroyLink($model)
    {
        return route('admin.group.destroy', $model);
    }
}
