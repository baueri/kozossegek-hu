<?php

namespace App\Portal\Controllers;

use App\Repositories\PageRepository;
use Framework\Http\Controller;
use Framework\Http\Request;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\View\View;

class PageController extends Controller
{
    /**
     * @param Request $request
     * @param PageRepository $repository
     * @return View|string
     */
    public function page(Request $request, PageRepository $repository)
    {
        $page = $repository->findBySlug($request['slug']);

        if (!$page) {
            raise_404();
        }

        $page_title = $page->pageTitle();

        if (view()->exists($view = "pages.{$request['slug']}")) {
            return view($view, compact('page', 'page_title'));
        }

        $model = compact('page', 'page_title');

        if ($page->header_image) {
            $model['header_background'] = $page->header_image;
        }

        return view('portal.page', $model);
    }
}
