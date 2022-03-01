<?php

namespace App\Admin\Institute;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Repositories\UsersLegacy;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use Framework\Support\StringHelper;
use App\Models\Institute;

class InstituteAdminTable extends AdminTable implements Deletable, Editable
{
    protected array $columns = [
        'id' => '<i class="fa fa-hashtag"></i>',
        'image' => '<i class="fa fa-image" title="Kép"></i>',
        'name' => 'Intézmény / plébánia neve',
        'leader_name' => 'Intézményvezető',
        'groups_count' => '<i class="fa fa-comments" title="Közösségek száma"></i>',
        'address' => 'Cím',
        'updated_at' => 'Módosítva',
        'user' => 'Létrehozta'
    ];

    protected array $sortableColumns = ['id', 'updated_at', 'groups_count'];

    protected array $centeredColumns = ['image', 'groups_count'];

    public function __construct(Request $request, private InstituteRepository $repository, private UsersLegacy $userRepository)
    {
        parent::__construct($request);
    }

    public function getDeleteUrl($model): string
    {
        return route('admin.institute.delete', ['id' => $model->id]);
    }

    protected function getData(): PaginatedResultSetInterface
    {
        $filter = $this->request;
        $institutes = $this->repository->getInstitutes($filter);
        $userIds = $institutes->pluck('user_id')->filter()->unique()->all();
        $users = $this->userRepository->getUsersByIds($userIds);
        $institutes->with($users, 'user', 'user_id');
        return $institutes;
    }

    public function getLeaderName($leader_name): string
    {
        $shortName = StringHelper::shorten($leader_name, 20, '...');
        return "<span title='{$leader_name}'>$shortName</span>";
    }

    public function getUser($user): string
    {
        return $user ? $user->name : '';
    }

    public function getName($value, Institute $institute): string
    {
        $warning = !$institute->approved ? '<i class="fa fa-exclamation-circle text-danger" title="még nem jóváhagyott intézmény"></i> ' : '';
        return $warning . $this->getEdit($value, $institute, 40) . " <small>{$institute->name2}</small>";
    }

    public function getImage($img, Institute $institute): string
    {
        $imageUrl = $institute->hasImage() ? $institute->getImageRelPath() . '?' . time() : '/images/default_thumbnail.jpg';
        return "<img src='$imageUrl' style='max-width: 25px; height: auto;' title='<img src=\"$imageUrl\">' data-html='true'/>";
    }

    public function getEditUrl($model): string
    {
        return route('admin.institute.edit', ['id' => $model->id]);
    }

    public function getEditColumn(): string
    {
        return 'name';
    }

    public function getAddress($address, Institute $institute): string
    {
        return static::excerpt(implode(', ', array_filter([$institute->city, $institute->district, $address])));
    }

    public function getGroupsCount($count, Institute $institute): string
    {
        $count = $count ?: 0;
        $url = route('admin.group.list', ['institute_id' => $institute->getId()]);

        return "<a href='$url' title='közösségek mutatása'>{$count}</a>";
    }
}
