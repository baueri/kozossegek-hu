<?php

namespace App\Admin\Institute;

use App\Admin\Components\AdminTable;
use App\Repositories\InstituteRepository;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;

/**
 * Description of InstituteAdminTable
 *
 * @author ivan
 */
class InstituteAdminTable extends AdminTable
{

    protected $columns = [
        'id' => '#',
        'name' => 'Intézmény neve',
        'leader_name' => 'Intézményvezető',
        'city' => 'Város',
        'address' => 'Cím'
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

    //put your code here
    protected function getData(): PaginatedResultSetInterface
    {
        return $this->repository->getInstitutes();
    }

}
