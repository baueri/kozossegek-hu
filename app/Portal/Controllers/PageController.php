<?php


namespace App\Portal\Controllers;


use App\Repositories\PageRepository;
use Framework\Http\Controller;
use Framework\Http\Request;

class PageController extends Controller
{
    public function page(Request $request, PageRepository $repository)
    {
        $page = $repository->findBySlug($request->getUriValue('slug'));
        return $this->view('portal.page', compact('page'));
    }
}