<?php

namespace App\Admin\Page;

use App\Admin\Controllers\AdminController;
use Framework\Http\Request;
use App\Repositories\PageRepository;
use Framework\Http\View\ViewInterface;

class PageController extends AdminController
{

    /**
     * @var PageRepository
     */
    private $repository;

    /**
     * @var \Framework\Http\Request
     */
    private $request;

    /**
     * 
     * @param ViewInterface $view
     * @param Request $request
     * @param PageRepository $repository
     */
    public function __construct(ViewInterface $view, \Framework\Http\Request $request, AdminPageRepository $repository) {
        parent::__construct($view);
        $this->request = $request;
        $this->repository = $repository;
    }
    
    public function list(PageTable $table)
    {
        $is_trash = false;
        $filter = $this->request;
        return $this->view('admin.page.list', compact('table', 'is_trash', 'filter'));
    }
    
    public function trash(TrashPageTable $table)
    {
        $is_trash = true;
        $filter = $this->request;
        return $this->view('admin.page.list', compact('table', 'is_trash', 'filter'));
    }
    
    public function create()
    {
        $action = route('admin.page.do_create');
        return $this->view('admin.page.create', compact('action'));
    }
    
    public function doCreate()
    {
        $this->repository->create($this->request->only('title', 'slug', 'content', 'status'));
        redirect('admin.page.list');
    }
    
    public function edit()
    {
        $page = $this->repository->findOrFail($this->request['id']);
        $action = route('admin.page.update', ['id' => $page->id]);
        return $this->view('admin.page.edit', compact('page', 'action'));
    }
    
    public function update()
    {
        $post = $this->repository->find($this->request['id']);
        
        $post->update($this->request->only('title', 'slug', 'content', 'status'));
        
        $this->repository->save($post);
        
        return redirect('admin.page.edit', ['id' => $this->request['id']]);
        
    }
    
    public function delete()
    {
        $this->repository->delete($this->repository->findOrFail($this->request['id']));
        
        return redirect('admin.page.list');
    }
}