<?php


namespace App\Portal\Controllers;
use Framework\Http\Request;
use App\Portal\Services\ImageService;

class ImageController
{
    public function getImageWithWatermark(Request $request, ImageService $image)
    {
        return $image->getImageWithWatermark($request);
    }
}
