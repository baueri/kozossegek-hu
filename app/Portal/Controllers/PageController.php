<?php

namespace App\Portal\Controllers;

use App\QueryBuilders\Pages;
use Framework\Http\Request;
use Framework\Http\View\View;

class PageController extends PortalController
{
    public function page(Request $request, Pages $repository): string
    {
        $page = $repository->whereSlug($request['slug'])->first();

        if (!$page) {
            raise_404();
        }

        $page_title = $page->pageTitle();

        if (View::exists($view = "pages.{$request['slug']}")) {
            return view($view, compact('page', 'page_title'));
        }

        $model = compact('page', 'page_title');

        if ($page->header_image) {
            $model['header_background'] = $page->header_image;
        }

        return view('portal2.page', $model);
    }
}
