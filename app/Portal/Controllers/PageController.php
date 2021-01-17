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

        $page_title = $page->pageTitle();

        if (view()->exists($view = "pages.{$request['slug']}")) {
            return view($view, compact('page', 'page_title'));
        }

        $header_background = $page->header_image ?: null;

        return view('portal.page', compact('page', 'page_title', 'header_background'));
    }
}
