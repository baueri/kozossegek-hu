<?php

namespace App\Portal\Controllers;

use Framework\Http\Request;
use App\Portal\Services\ImageService;

class ImageController
{
    public function getImage(Request $request, ImageService $image)
    {
        if ($request['entity_type'] == 'institutes') {
            $image->getInstituteImage($request['image']);
        } else {
            $image->getGroupImage($request['image']);
        }
    }
}
