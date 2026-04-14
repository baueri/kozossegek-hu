<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\QueryBuilders\Pages;
use Framework\Http\Request;

class NewsController extends PortalController
{
    public function list(): string
    {
        $news = Pages::query()
            ->news()
            ->orderBy('created_at', 'desc')
            ->published()
            ->paginate(12);
        return $this->mintView->render('pages/hirek.php', compact('news'));
    }

    public function view(Request $request): string
    {
        $entry = Pages::query()->whereSlug($request['slug'])->firstOrFail();
        return $this->mintView->render('pages/hirek-bejegyzes.php', compact('entry'));
    }
}
