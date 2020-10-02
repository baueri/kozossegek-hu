<?php


namespace App\Portal\Controllers;


use App\Repositories\PageRepository;
use Framework\Http\Controller;
use Framework\Http\Request;
use Framework\Http\Exception\PageNotFoundException;

class PageController extends Controller
{
    public function page(Request $request, PageRepository $repository)
    {
        $page = $repository->findBySlug($request['slug']);

        if (!$page) {
            throw new PageNotFoundException('page not found');
        }

        return view('portal.page', compact('page'));
    }
}
