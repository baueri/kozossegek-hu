<?php

namespace App\Admin\Controllers;

use App\Admin\Page\PageTable;
use App\Admin\Page\Services\PageListService;
use App\Admin\Page\TrashPageTable;
use App\Auth\Auth;
use App\Models\Page;
use App\Portal\Services\PageRenderService;
use App\Repositories\AdminPageRepository;
use App\Repositories\PageRepository;
use Framework\Http\Message;
use Framework\Http\Request;

class PageController extends AdminController
{

    /**
     * @var PageRepository
     */
    private $repository;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var PageRenderService
     */
    private PageRenderService $pageRenderService;

    /**
     *
     * @param Request $request
     * @param AdminPageRepository $repository
     */
    public function __construct(Request $request, AdminPageRepository $repository)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    public function list(PageTable $table, PageListService $service)
    {
        return $service->show($table);
    }

    public function trash(TrashPageTable $table, PageListService $service)
    {
        return $service->show($table);
    }

    public function emptyTrash(PageRepository $repository)
    {
        $repository->getBuilder()->whereNotNull('deleted_at')->delete();

        Message::warning('Lomtár kiürítve.');

        redirect_route('admin.page.trash');
    }

    public function create()
    {
        $page = new Page();
        $action = route('admin.page.do_create');
        return view('admin.page.create', compact('action', 'page'));
    }

    public function doCreate()
    {
        $data = $this->request->only('title', 'slug', 'content', 'status');
        $data['user_id'] = Auth::user()->id;

        $page = $this->repository->create($data);

        Message::success('Oldal létrehozva');

        redirect_route('admin.page.edit', ['id' => $page->id]);
    }

    public function edit()
    {
        $page = $this->repository->findOrFail($this->request['id']);
        $action = route('admin.page.update', ['id' => $page->id]);
        return view('admin.page.edit', compact('page', 'action'));
    }

    public function update()
    {
        $page = $this->repository->find($this->request['id']);

        $page->update($this->request->only('title', 'slug', 'content', 'status', 'header_image'));

        $this->repository->save($page);

        Message::success('Oldal frissítve');

        redirect_route('admin.page.edit', $page);
    }

    public function delete()
    {
        $this->repository->deleteById($this->request['id']);

        Message::warning('Oldal lomtárba helyezve');

        redirect_route('admin.page.list');
    }

    public function forceDelete()
    {
        $this->repository->deleteById($this->request['id'], true);

        Message::warning('Oldal véglegesen törölve');

        redirect_route('admin.page.trash');
    }

    public function restore(Request $request, PageRepository $repository)
    {
        try {
            $repository->restorePageById($request['id']);
            Message::success('Sikeres visszaállítás');
        } catch (\Exception $e) {
            Message::danger('Sikertelen visszaállítás');
        } finally {
            redirect_route('admin.page.trash');
        }

    }
}
