<?php

namespace App\Admin\Institute;

/**
 * Description of InstituteAdminTable
 *
 * @author ivan
 */
class InstituteAdminTable extends \App\Admin\Components\AdminTable {

    /**
     * @var App\Repositories\InstituteRepository
     */
    private $repository;
    
    
    protected $columns = [
        'id' => '#',
        'name' => 'Intézmény neve',
        'leader_name' => 'Intézményvezető',
        'city' => 'Város',
        'address' => 'Cím'
    ];

    public function __construct(\Framework\Http\View\ViewInterface $view, \Framework\Http\Request $request, \App\Repositories\InstituteRepository $repository) {
        parent::__construct($view, $request);
        $this->repository = $repository;
    }
    
    //put your code here
    protected function getData(): \Framework\Database\PaginatedResultSetInterface {
        return $this->repository->getInstitutes();
    }

}
