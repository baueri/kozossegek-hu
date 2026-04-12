<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\QueryBuilders\Pages;
use Framework\Http\Request;

class PageController extends PortalController
{
    public function page(Request $request, Pages $repository): string
    {
        use_default_header_bg();

        $slug = $request->getUriValue('slug');

        if (!$slug || !($page = $repository->whereSlug($slug)->first())) {
            log_event('');
            raise_404();
        }

        $page_title = $page->pageTitle();
        $subtitle = $page->title . ' | ';
        $pageTitle = $page_title;

        $model = compact('page', 'page_title', 'subtitle', 'pageTitle');

        if ($page->header_image) {
            $model['header_background'] = $page->header_image;
        }

        $mintTemplate = "pages/{$slug}.php";

        if (file_exists($this->mintView->viewsPath . '/' . $mintTemplate)) {
            return $this->mintView->render($mintTemplate, $model);
        }

        return $this->mintView->render('pages/page.php', $model);
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
