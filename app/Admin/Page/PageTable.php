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
        'status' => 'Állapot'
    ];

    /**
     * PageTable constructor.
     * @param ViewInterface $view
     * @param PageRepository $repository
     */
    public function __construct(ViewInterface $view, Request $request, PageRepository $repository)
    {
        parent::__construct($view, $request);
        $this->repository = $repository;
    }

    public function getSlug($slug)
    {
        $url = get_site_url() . '/' . $slug;
        return "<a href='http://$url' target='_blank'>http://$url</a>";
    }

    public function getStatus($status)
    {
        return (new PageStatus($status))->translate();
    }

    protected function getData(): PaginatedResultSetInterface
    {
        return $this->repository->getPages();
    }
}