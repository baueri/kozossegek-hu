<?php
namespace App\Admin\Page\Services;

use App\Admin\Page\AdminPageRepository;
use App\Admin\Page\PageTable;
use App\Admin\Page\TrashPageTable;
use Framework\Http\Request;

class PageListService
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var AdminPageRepository
     */
    private $repository;

    public function __construct(Request $request, AdminPageRepository $repository)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    public function show(PageTable $table)
    {
        $is_trash = $table instanceof TrashPageTable;

        $filter = $this->request;
        $trash_count = $this->repository->getBuilder()->whereNotNull('deleted_at')->count();
        return view('admin.page.list', compact('table', 'is_trash', 'filter', 'trash_count'));
    }
}