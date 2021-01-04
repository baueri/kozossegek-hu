<?php

namespace App\Admin\Controllers;

use App\Admin\Page\Services\PageListService;
use Framework\Http\Message;
use Framework\Http\Request;
use App\Repositories\PageRepository;
use App\Auth\Auth;
use App\Models\Page;
use App\Repositories\AdminPageRepository;
use App\Admin\Page\PageTable;
use App\Admin\Page\TrashPageTable;

class PageController extends AdminController
{

    /**
     * @var PageRepository
     */
    private $repository;

    /**
     * @var Request
     */
    private $request;

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

        $hasUploadableImages = preg_match_all('/data\-filename="[a-zA-Z0-9\.\_]"/im', $data['content'], $images);

        if ($hasUploadableImages) {
            dd($images);
        }

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
        $post = $this->repository->find($this->request['id']);

        $post->update($this->request->only('title', 'slug', 'content', 'status'));

        $this->repository->save($post);

        Message::success('Oldal frissítve');

        return redirect_route('admin.page.edit', ['id' => $this->request['id']]);

    }

    public function delete()
    {
        $this->repository->delete($this->repository->findOrFail($this->request['id']));

        Message::warning('Oldal lomtárba helyezve');

        return redirect_route('admin.page.list');
    }
}
