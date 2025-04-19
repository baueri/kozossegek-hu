<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\QueryBuilders\Pages;
use Framework\Http\Request;
use Framework\Http\View\View;

class PageController extends PortalController
{
    public function page(Request $request, Pages $repository): string
    {
        use_default_header_bg();

        // @todo ez most csak egy workaround, a routing-ot kellene ugy atalakitani, hogy egyszerubben lehessen regex pattern-t
        // @todo is megadni
        preg_match('/^\/([a-z0-9-]+)\/?$/', $request->uri, $matches);
        $slug = $matches[1];

        if (!$slug || !($page = $repository->whereSlug($slug)->first())) {
            log_event('');
            raise_404();
        }

        $page_title = $page->pageTitle();

        if (View::exists($view = "pages.{$slug}")) {
            return view($view, compact('page', 'page_title'));
        }

        $model = compact('page', 'page_title');

        if ($page->header_image) {
            $model['header_background'] = $page->header_image;
        }

        return view('portal.page', $model);
    }

    public function setAnnouncementsSeen(): void
    {
        $ids = request()->get('ids');

        builder('seen_announcements')
            ->where('user_id', auth()->getId())
            ->whereIn('announcement_id', $ids)
            ->whereNull('seen_at')
            ->update(['seen_at' => now()]);
    }
}
