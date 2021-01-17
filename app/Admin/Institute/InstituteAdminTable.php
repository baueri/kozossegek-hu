<?php

namespace App\Admin\Institute;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Models\Institute;
use App\Repositories\Institutes;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use App\Repositories\Users;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

/**
 * Description of InstituteAdminTable
 *
 * @author ivan
 */
class InstituteAdminTable extends AdminTable implements Deletable, Editable
{

    protected $columns = [
        'id' => '<i class="fa fa-hashtag"></i>',
        'image' => '<i class="fa fa-image" title="Kép"></i>',
        'name' => 'Intézmény / plébánia neve',
        'leader_name' => 'Plébános / intézményvezető',
        'group_count' => '<i class="fa fa-comments" title="Közösségek"></i>',
        'city' => 'Település',
        'district' => 'Városrész',
        'address' => 'Cím',
        'updated_at' => 'Utoljára módosítva',
        'user' => 'Létrehozta'
    ];

    protected array $centeredColumns = ['image', 'group_count'];

    /**
     * @var Institutes
     */
    private Institutes $repository;

    /**
     * @var Users
     */
    private Users $userRepository;

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
        $users = $this->userRepository->getUsersByIds($userIds->unique()->all());
        $groupsCount = $this->getNumberOfGroups($institutes);
        $institutes->with($users, 'user', 'user_id')
            ->withCount($groupsCount, 'group_count', 'id', 'institute_id');

        return $institutes;
    }

    public function getLeaderName($leader_name)
    {
        $shortName = StringHelper::shorten($leader_name, 20, '...');
        return "<span title='{$leader_name}'>$shortName</span>";
    }

    public function getUser($user)
    {
        return $user->name;
    }

    public function getName($value, Institute $institute)
    {
        $warning = !$institute->approved ? '<i class="fa fa-exclamation-circle text-danger" title="még nem jóváhagyott intézmény"></i> ' : '';
        return $warning . $this->getEdit($value, $institute);
    }

    public function getImage($img, Institute $institute)
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

    public function getAddress($address)
    {
        return static::excerpt($address);
    }

    public function getGroupCount($count)
    {
        return $count ?: 0;
    }

    private function getNumberOfGroups(Collection $institutes)
    {
        $ids = $institutes->pluck('id')->implode(',');
        return db()->select("select count(*) as cnt, institute_id from groups where institute_id in ($ids) and deleted_at is null group by institute_id");
    }
}
