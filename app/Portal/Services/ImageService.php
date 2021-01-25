<?php

namespace App\Portal\Services;

use App\Helpers\InstituteHelper;
use Framework\Http\Request;
use App\Helpers\GroupHelper;
use Framework\Http\Response;

class ImageService
{
    public function getGroupImage(string $image)
    {

        $path = $this->getGroupImagePath($image);

        if (!file_exists($path)) {
            Response::setStatusCode('404');
            return;
        }

        header('Pragma: public');
        header('Cache-Control: max-age=86400');

        image_with_watermark($path);
    }

    public function getInstituteImage(string $image)
    {
        $imgPath = $this->getInstituteImagePath($image);

        header('Pragma: public');
        header('Cache-Control: max-age=86400');

        $mime_type = mime_content_type($imgPath);
        header("Content-Type: {$mime_type}");

        $im = imagecreatefromjpeg($imgPath);
        imagejpeg($im);
    }

    private function getGroupImagePath($image)
    {
        [$groupId] = explode('_', $image);
        return GroupHelper::getStoragePath($groupId) . $image;
    }

    private function getInstituteImagePath($image)
    {
        [,$instituteId] = explode('inst_', $image);
        return InstituteHelper::getImageStoragePath(substr($instituteId, 0, strpos($instituteId, '.jpg')));
    }
}
