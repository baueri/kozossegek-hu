<?php

namespace App\Portal\Controllers;

use App\Middleware\LegalNoticeMiddleware;
use Baueri\Mint\View as MintEngineView;
use Framework\Http\Controller;
use Framework\Http\Request;

abstract class PortalController extends Controller
{
    public function __construct(Request $request, protected MintEngineView $mintView)
    {
        parent::__construct($request);
    }

    public function bootPortalController(): void
    {
        $this->middleware(LegalNoticeMiddleware::class);
    }
}
