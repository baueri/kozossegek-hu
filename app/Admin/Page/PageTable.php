<?php


namespace App\Admin\Page;


use App\Admin\Components\AdminTable;
use App\Models\Page;
use App\Models\PageStatus;
use App\Repositories\PageRepository;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use App\Repositories\UserRepository;

class PageTable extends AdminTable
{
    /**
     * @var PageRepository
     */
    private $repository;
    
    /**
     * @var UserRepository
     */
    private $userRepository;

    protected $columns = [
        'id' => '#',
        'title' => 'Oldal címe',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'updated_at' => 'Utoljára módosítva',
        'delete' => '<i class="fa fa-trash"></i>'
    ];

    /**
     * PageTable constructor.
     * @param Request $request
     * @param AdminPageRepository $repository
     */
    public function __construct(Request $request, AdminPageRepository $repository, UserRepository $userRepository)
    {
        parent::__construct($request);
        $this->repository = $repository;
        $this->userRepository = $userRepository;
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
    
    public function getTitle($title, Page $page)
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

    public function getUpdatedAt($updatedAt)
    {
        if (!$updatedAt) {
            return '-';
        }
        return date('Y.m.d H:i', strtotime($updatedAt));
    }
    
    public function getUserId(...$params)
    {
        [,$page] = $params;
        
        return $page->user->name;
    }

    protected function getData(): PaginatedResultSetInterface
    {
        $filter = $this->request->all();
        if ($this->request->route->getAs() == 'admin.page.trash') {
            $filter['deleted'] = true;
        }
        
        $pages = $this->repository->getPages($filter);
        
        $userIds = $pages->pluck('user_id')->unique()->all();
        
        $pages->with($this->userRepository->getUsersByIds($userIds), 'user', 'user_id');
        
        return $pages;
    }
}