<?php

namespace App\Portal\Controllers\Api\V1;

use App\Http\Responses\CreateGroupSteps\FinishRegistration;
use Framework\Http\Controller;
use Framework\Http\Request;

class ApiGroupController extends Controller
{
    public function previewGroup(Request $request, FinishRegistration $preview)
    {
        return (string) $preview;
    }
}
