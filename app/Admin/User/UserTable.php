<?php

namespace App\Admin\User;

use App\Enums\UserGroup;
use App\Models\User;
use App\Repositories\Groups;
use Framework\Database\Builder;
use Framework\Database\PaginatedResultSetInterface;
use App\Repositories\Users;
use Framework\Http\Request;
use App\Admin\Components\AdminTable\ {
    AdminTable, Deletable, Editable
};
use Framework\Support\Collection;

class UserTable extends AdminTable implements Deletable, Editable
{
    protected $columns = [
        'id' => '#',
        'groups' => '<i class="fa fa-comments"></i>',
        'name' => 'Név',
        'user_group' => 'Jogosultság',
        'email' => 'Email',
        'activated_at' => 'Aktiválva',
        'created_at' => 'Regisztráció',
    ];

    private Users $repository;

    private Groups $groups;

    public function __construct(Users $repository, Groups $groups, Request $request)
    {
        $this->repository = $repository;
        $this->groups = $groups;
        parent::__construct($request);
    }

    public function getDeleteUrl($model): string
    {
        return route('admin.user.delete', $model);
    }

    public function getEditUrl($model): string
    {
        return route('admin.user.edit', $model);
    }

    public function getEditColumn(): string
    {
        return 'name';
    }

    public function getUserGroup($userGroup)
    {
        return UserGroup::of($userGroup)->text();
    }

    public function getActivatedAt($activatedAt)
    {
        if ($activatedAt) {
            return static::getCheckIcon();
        }

        return static::getBanIcon();
    }

    public function getCreatedAt($date)
    {
        return date('Y.m.d', strtotime($date));
    }

    public function getGroups($g, User $user)
    {
        $icon = static::getIcon('fa fa-comments');
        $route = route('admin.group.list', ['user_id' => $user->id]);
        $groupCount = (int) $user->group_count;
        return static::getLink(
            $route,
            "{$icon} ({$groupCount})",
            "karbantartott közösség(ek)"
        );
    }

    public function getData(): PaginatedResultSetInterface
    {
        $filter = collect($this->request->only('deleted', 'search', 'user_group'));
        $filter['sort'] = $this->request['sort'] ?: 'desc';
        $filter['order_by'] = $this->request['order_by'] ?: 'id';

        return $this->getUsers($filter);
    }

    private function getNumberOfGroups(Collection $users)
    {
        if ($users->isEmpty()) {
            return [];
        }
        $ids = $users->pluck('id')->implode(',');
        return db()->select("select count(*) as cnt, user_id from church_groups where user_id in ($ids) and deleted_at is null group by user_id");
    }

    private function getUsers(Collection $filter)
    {
        $query = $this->repository->query();

        if ($filter['deleted']) {
            $query->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        if ($filter['order_by']) {
            $query->orderBy($filter['order_by'], $filter['sort']);
        }

        if ($search = $filter['search']) {
            $query->where(function (Builder $query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($userGroup = $filter['user_group']) {
            $query->where('user_group', $userGroup);
        }

        $users = $query->paginate($this->perpage);

        return $users->withCount($this->getNumberOfGroups($users), 'group_count', 'id', 'user_id');
    }
}
