<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\QueryBuilders\Pages;
use Framework\Http\Request;

class NewsController extends PortalController
{
    public function list(): string
    {
        $news = Pages::query()->news()->paginate();
        return view('portal.news.list', compact('news'));
    }

    public function view(Request $request): string
    {
        $page = Pages::query()->whereSlug($request['slug'])->firstOrFail();
        return view('portal.news.view', compact('page'));
    }
}
