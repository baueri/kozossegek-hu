<?php

declare(strict_types=1);

namespace App\Admin\Controllers;

use App\Admin\Page\PageTable;
use App\Admin\Page\Services\PageListService;
use App\Admin\Page\TrashPageTable;
use App\Auth\Auth;
use App\Models\Page;
use App\QueryBuilders\Pages;
use Exception;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\View\View;
use Framework\Model\Exceptions\ModelNotFoundException;

class PageController extends AdminController
{
    public function __construct(
        Request $request,
        private readonly Pages $repository
    ) {
        parent::__construct($request);
        View::setVariable('trash_count', Pages::query()->trashed()->count());
    }

    public function list(PageTable $table, PageListService $service): string
    {
        return $service->show($table);
    }

    public function trash(TrashPageTable $table, PageListService $service): string
    {
        return $service->show($table);
    }

    public function emptyTrash(): never
    {
        $this->repository->trashed()->delete();

        Message::warning('Lomtár kiürítve.');

        redirect($this->request->referer());
    }

    public function create(): string
    {
        $page = new Page();
        $action = route('admin.page.do_create');
        $page_type = $this->request->get('page_type', 'page');
        return view('admin.page.create', compact('action', 'page', 'page_type'));
    }

    public function doCreate(): never
    {
        $data = $this->request->only('title', 'slug', 'content', 'status', 'page_type', 'header_image');
        $data['user_id'] = Auth::user()->id;

        $page = $this->repository->create($data);

        Message::success('Oldal létrehozva');

        redirect_route('admin.page.edit', ['id' => $page->id]);
    }

    public function edit(): string
    {
        $page = $this->repository->find($this->request['id']);
        if (!$page) {
            Message::danger('a keresett oldal nem található');
            redirect_route('admin.page.list');
        }
        $action = route('admin.page.update', ['id' => $page->id]);
        $page_type = $page->page_type;
        return view('admin.page.edit', compact('page', 'action', 'page_type'));
    }

    /**
     * @throws ModelNotFoundException
     */
    public function update(): never
    {
        $page = $this->repository->findOrFail($this->request['id']);

        $this->repository->save($page, $this->request->only('title', 'slug', 'content', 'status', 'header_image'));

        Message::success('Oldal frissítve');

        redirect_route('admin.page.edit', $page);
    }

    public function delete(): never
    {
        $this->repository->deleteModel($this->request['id']);

        Message::warning('Oldal lomtárba helyezve');

        redirect($this->request->referer());
    }

    public function forceDelete(): never
    {
        $this->repository->deleteModel($this->request['id'], true);

        Message::warning('Oldal véglegesen törölve');

        redirect($this->request->referer());
    }

    public function restore(Request $request): void
    {
        try {
            $this->repository->where('id', $request['id'])->update(['deleted_at' => null]);
            Message::success('Sikeres visszaállítás');
        } catch (Exception $e) {

            Message::danger('Sikertelen visszaállítás');
        } finally {
            redirect($this->request->referer());
        }
    }
}
