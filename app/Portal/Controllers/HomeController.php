<?php

namespace App\Portal\Controllers;

use App\Repositories\PageRepository;
use Framework\Http\Controller;

/**
 * Description of HomeController
 *
 * @author ivan
 */
class HomeController extends Controller
{
    public function home(PageRepository $pages)
    {
        use_default_header_bg();

        $content = $pages->findBySlug('fooldal')->content;

        return view('portal.home', compact('content'));
    }
}
