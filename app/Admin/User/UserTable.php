<?php

namespace App\Admin\User;

use App\Enums\UserRole;
use App\Admin\Components\AdminTable\{PaginatedAdminTable, Editable};
use App\Admin\Components\AdminTable\Traits\SoftDeletable;
use App\Models\User;
use App\Models\UserSession;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Users;
use App\QueryBuilders\UserSessions;
use Framework\Database\Builder;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use Framework\Model\PaginatedModelCollection;
use Framework\Support\Collection;

class UserTable extends PaginatedAdminTable implements Editable
{
    use SoftDeletable;

    protected array $columns = [
        'id' => '#',
        'groups' => '<i class="fa fa-comments"></i>',
        'name' => 'Név',
        'user_role' => 'Jogosultság',
        'email' => 'Email',
        'activated_at' => 'Aktiválva',
        'created_at' => 'Regisztráció',
        'last_login' => 'Utoljára belépve',
        'sessions' => '<i class="fa fa-dot-circle text-success" title="Online"></i>'
    ];

    protected array $sortableColumns = [
        'activated_at',
        'created_at',
        'last_login'
    ];

    private Users $repository;

    public function __construct(Users $repository, Request $request)
    {
        $this->repository = $repository;
        parent::__construct($request);
    }

    public function getSoftDeleteLink($model): string
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

    public function getUserRole($userRole): string
    {
        return UserRole::from($userRole)->translate();
    }

    public function getActivatedAt($activatedAt): string
    {
        if ($activatedAt) {
            return static::getCheckIcon();
        }

        return static::getBanIcon();
    }

    public function getCreatedAt($date): string
    {
        return date('Y.m.d', strtotime($date));
    }

    public function getGroups($g, User $user): string
    {
        $icon = static::getIcon('fa fa-comments');
        $route = route('admin.group.list', ['user_id' => $user->id]);
        $groupCount = $user->groups_count;
        return static::getLink(
            $route,
            "{$icon} ({$groupCount})",
            "karbantartott közösség(ek)"
        );
    }

    /**
     * @param Collection<UserSession> $sessions
     * @return string
     */
    public function getSessions(Collection $sessions): string
    {
        if ($sessions->isEmpty()) {
            return static::getIcon('fa fa-dot-circle text-lightgray');
        }

        $lastSession = $sessions->first();

        if ($lastSession->updatedAt()->gt(now()->subMinutes(30))) {
            return static::getIcon('fa fa-dot-circle text-success');
        }

        return static::getIcon('fa fa-dot-circle text-warning');
    }

    public function getData(): PaginatedResultSetInterface
    {
        $filter = collect($this->request->only('deleted', 'search', 'user_role'));
        $filter['sort'] = $this->request['sort'] ?: 'desc';
        $filter['order_by'] = $this->request['order_by'] ?: 'id';

        return $this->getUsers($filter);
    }

    private function getUsers(Collection $filter): PaginatedModelCollection
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

        if ($user_role = $filter['user_role']) {
            $query->where('user_role', UserRole::from($user_role));
        }

        $query->withCount('groups', fn(ChurchGroups $groupViews) => $groupViews->active());
        $online = ['sessions', fn (UserSessions $query) => $query->online()->orderBy('updated_at', 'desc')];
        $query->with(...$online);

        if ($this->request->get('online')) {
            $query->whereHas(...$online);
        }

        return $query->paginate($this->perpage);
    }
}
