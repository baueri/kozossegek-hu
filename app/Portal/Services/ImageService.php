<?php

declare(strict_types=1);

namespace App\Portal\Services;

use App\Helpers\InstituteHelper;
use App\Helpers\GroupHelper;
use Framework\Http\Response;

class ImageService
{
    public function printGroupImage(string $image): void
    {
        $path = $this->getGroupImagePath($image);

        if (!file_exists($path)) {
            Response::setStatusCode('404');
            return;
        }

        $img = imagecreatefromjpeg($path);

        header('Pragma: public');
        header('Cache-Control: max-age=86400');

        ob_start();
        imagejpeg($img);

        $mime_type = mime_content_type($path);
        header("Content-Type: {$mime_type}");
    }

    public function printInstituteImage(string $image): void
    {
        $imgPath = $this->getInstituteImagePath($image);

        header('Pragma: public');
        header('Cache-Control: max-age=86400');

        $mime_type = mime_content_type($imgPath);
        header("Content-Type: {$mime_type}");

        $im = imagecreatefromjpeg($imgPath);
        imagejpeg($im);
    }

    private function getGroupImagePath($image): string
    {
        [$groupId] = explode('_', $image);
        return GroupHelper::getStoragePath($groupId) . $image;
    }

    private function getInstituteImagePath($image): string
    {
        [,$instituteId] = explode('inst_', $image);
        return InstituteHelper::getImageStoragePath(substr($instituteId, 0, strpos($instituteId, '.jpg')));
    }
}
