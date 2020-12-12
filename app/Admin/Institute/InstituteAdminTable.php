<?php

namespace App\Admin\Institute;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Repositories\Institutes;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use App\Repositories\Users;

/**
 * Description of InstituteAdminTable
 *
 * @author ivan
 */
class InstituteAdminTable extends AdminTable implements Deletable, Editable
{

    protected $columns = [
        'id' => '#',
        'name' => 'Intézmény / plébánia neve',
        'leader_name' => 'Plébános / intézményvezető',
        'city' => 'Település',
        'district' => 'Városrész',
        'address' => 'Cím',
        'updated_at' => 'Utoljára módosítva',
        'user' => 'Létrehozta'
    ];

    /**
     * @var Institutes
     */
    private $repository;

    /**
     * @var Users
     */
    private $userRepository;

    /**
     * InstituteAdminTable constructor.
     * @param Request $request
     * @param Institutes $repository
     */
    public function __construct(Request $request, Institutes $repository, Users $userRepository)
    {
        parent::__construct($request);
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    public function getDeleteUrl($model): string
    {
        return route('admin.institute.delete', ['id' => $model->id]);
    }

    protected function getData(): PaginatedResultSetInterface
    {
        $institutes = $this->repository->getInstitutesForAdmin($this->request);
        $userIds = $institutes->pluck('user_id');
        $users = $this->userRepository->getUsersByIds($userIds->all());

        $institutes->with($users, 'user', 'user_id');

        return $institutes;
    }

    public function getUser($user)
    {
        return $user->name;
    }

    public function getEditUrl($model): string
    {
        return route('admin.institute.edit', ['id' => $model->id]);
    }

    public function getEditColumn(): string
    {
        return 'name';
    }
}
