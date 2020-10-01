<?php

namespace App\Admin\Institute;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Repositories\InstituteRepository;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;

/**
 * Description of InstituteAdminTable
 *
 * @author ivan
 */
class InstituteAdminTable extends AdminTable implements Deletable, Editable
{

    protected $columns = [
        'id' => '#',
        'name' => 'Intézmény neve',
        'leader_name' => 'Intézményvezető',
        'city' => 'Város',
        'address' => 'Cím',
    ];

    /**
     * @var InstituteRepository
     */
    private $repository;

    /**
     * InstituteAdminTable constructor.
     * @param Request $request
     * @param InstituteRepository $repository
     */
    public function __construct(Request $request, InstituteRepository $repository)
    {
        parent::__construct($request);
        $this->repository = $repository;
    }

    public function getDeleteUrl($model): string
    {
        return route('admin.institute.delete', ['id' => $model->id]);
    }

    protected function getData(): PaginatedResultSetInterface
    {
        return $this->repository->getInstitutesForAdmin();
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
