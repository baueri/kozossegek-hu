<?php


namespace App\Admin\Page;


use App\Admin\Components\AdminTable;
use App\Models\PageStatus;
use App\Repositories\PageRepository;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use Framework\Http\View\ViewInterface;

class PageTable extends AdminTable
{
    /**
     * @var PageRepository
     */
    private $repository;

    protected $columns = [
        'id' => '#',
        'title' => 'Oldal címe',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'delete' => '<i class="fa fa-trash"></i>'
    ];

    /**
     * PageTable constructor.
     * @param ViewInterface $view
     * @param PageRepository $repository
     */
    public function __construct(ViewInterface $view, Request $request, AdminPageRepository $repository)
    {
        parent::__construct($view, $request);
        $this->repository = $repository;
    }

    public function getSlug($slug)
    {
        $url = route('portal.page', compact('slug')) ;
        return "<a href='$url' target='_blank'>$url</a>";
    }

    public function getStatus($status)
    {
        return (new PageStatus($status))->translate();
    }
    
    public function getTitle($title, \App\Models\Page $page)
    {
        $url = route('admin.page.edit', ['id' => $page->id]) ;
        return "<a href='$url'>$title</a>";
    }
    
    public function getDelete(...$params)
    {
        [,$page] = $params;
        
        $url = route('admin.page.delete', ['id' => $page->id]) ;
        return "<a href='$url' title='lomtárba'><i class='fa fa-trash text-danger'></i></a>";
    }

    protected function getData(): PaginatedResultSetInterface
    {
        $filter = $this->request->all();
        if ($this->request->route->getAs() == 'admin.page.trash') {
            $filter['deleted'] = true;
        }
        return $this->repository->getPages($filter);
    }
}