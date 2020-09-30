<?php

namespace App\Admin\Group;

use App\Admin\Components\AdminTable;
use App\Models\AgeGroup;
use App\Models\Group;
use App\Models\GroupStatus;
use App\Models\Institute;
use App\Repositories\GroupRepository;
use App\Repositories\InstituteRepository;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use Framework\Support\StringHelper;

class GroupTable extends AdminTable
{

    protected $columns = [
        'id' => '#',
        'name' => 'Közösség neve',
        'city' => 'Város',
        'institute' => 'Intézmény / plébánia',
        'group_leaders' => 'Közösség vezető(i)',
        'age_group' => 'Korosztály',
        'status' => 'Státusz',
        'created_at' => 'Létrehozva',
        'delete' => '<i class="fa fa-trash"></i>'
    ];
    protected $centeredColumns = ['status'];
    /**
     * @var GroupRepository
     */
    private $repository;
    /**
     * @var InstituteRepository
     */
    private $instituteRepository;

    /**
     * GroupTable constructor.
     * @param Request $request
     * @param GroupRepository $repository
     * @param InstituteRepository $instituteRepository
     */
    public function __construct(Request $request, GroupRepository $repository, InstituteRepository $instituteRepository)
    {
        parent::__construct($request);
        $this->repository = $repository;
        $this->instituteRepository = $instituteRepository;
    }

    public function getAgeGroup($ageGroup)
    {
        return (new AgeGroup($ageGroup))->translate();
    }

    public function getInstitute(?Institute $institute)
    {
        return $institute->name;
    }

    public function getName($name, Group $group)
    {
        $uri = route('admin.group.edit', ['id' => $group->id]);
        return "<a href='$uri'>$name <i class='fa fa-pencil-alt'></i></a>";
    }

    public function getCreatedAt($createdAt)
    {
        return date('Y.m.d H:i', strtotime($createdAt));
    }

    public function getDelete($col, Group $group)
    {
        $uri = route('admin.group.delete', ['id' => $group->id]);
        return "<a href='$uri' class='text-danger'><i class='fa fa-trash'></i>";
    }

    public function getStatus($status)
    {
        $status = new GroupStatus($status);
        $class = $status->getClass();
        $text = $status->translate();
        return "<i class='$class' title='$text'></i>";
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
        $groups = $this->repository->search($this->request->merge([
            'order_by' => 'id',
            'order' => 'desc'
        ]));

        $instituteIds = $groups->pluck('institute_id');
        $institutes = $this->instituteRepository->getInstitutesByIds($instituteIds->toArray());

        $groups->with($institutes, 'institute', 'institute_id', 'id');

        return $groups;
    }
}