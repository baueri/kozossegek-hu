<?php

namespace App\Portal\Controllers;

use App\Middleware\LegalNoticeMiddleware;
use Framework\Http\Controller;

abstract class PortalController extends Controller
{
    public function bootPortalController(): void
    {
        $this->middleware(LegalNoticeMiddleware::class);
    }
}
