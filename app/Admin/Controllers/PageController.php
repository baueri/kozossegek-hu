<?php

namespace App\Admin\Controllers;

use App\Admin\Page\PageTable;
use App\Admin\Page\Services\PageListService;
use App\Admin\Page\TrashPageTable;
use App\Auth\Auth;
use App\Models\Page;
use App\Repositories\AdminPageRepository;
use App\Repositories\PageRepository;
use Framework\Http\Message;
use Framework\Http\Request;

class PageController extends AdminController
{
    public function __construct(
        Request $request,
        private readonly AdminPageRepository $repository
    ) {
        parent::__construct($request);
    }

    public function list(PageTable $table, PageListService $service): string
    {
        return $service->show($table);
    }

    public function trash(TrashPageTable $table, PageListService $service): string
    {
        return $service->show($table);
    }

    public function emptyTrash(PageRepository $repository): never
    {
        $repository->getBuilder()->whereNotNull('deleted_at')->delete();

        Message::warning('Lomtár kiürítve.');

        redirect_route('admin.page.trash');
    }

    public function create(): string
    {
        $page = new Page();
        $action = route('admin.page.do_create');
        return view('admin.page.create', compact('action', 'page'));
    }

    public function doCreate(): never
    {
        $data = $this->request->only('title', 'slug', 'content', 'status');
        $data['user_id'] = Auth::user()->id;

        $page = $this->repository->create($data);

        Message::success('Oldal létrehozva');

        redirect_route('admin.page.edit', ['id' => $page->id]);
    }

    public function edit(): string
    {
        $page = $this->repository->findOrFail($this->request['id']);
        $action = route('admin.page.update', ['id' => $page->id]);
        return view('admin.page.edit', compact('page', 'action'));
    }

    public function update(): never
    {
        $page = $this->repository->find($this->request['id']);

        $page->update($this->request->only('title', 'slug', 'content', 'status', 'header_image'));

        $this->repository->save($page);

        Message::success('Oldal frissítve');

        redirect_route('admin.page.edit', $page);
    }

    public function delete(): never
    {
        $this->repository->deleteById($this->request['id']);

        Message::warning('Oldal lomtárba helyezve');

        redirect_route('admin.page.list');
    }

    public function forceDelete(): never
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
