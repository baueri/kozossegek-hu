<?php

namespace App\Portal\Controllers;

use Framework\Http\Request;
use App\Portal\Services\ImageService;

class ImageController
{
    public function getImage(Request $request, ImageService $image): void
    {
        if ($request['entity_type'] == 'institutes') {
            $image->printInstituteImage($request['image']);
        } else {
            $image->printGroupImage($request['image']);
        }
    }
}
