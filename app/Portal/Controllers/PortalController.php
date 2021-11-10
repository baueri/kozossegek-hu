<?php

namespace App\Portal\Controllers;

use App\Middleware\LegalNoticeMiddleware;
use Framework\Http\Controller;
use Framework\Http\Request;

abstract class PortalController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->middleware(LegalNoticeMiddleware::class);
    }
}
