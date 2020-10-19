<?php

namespace App\Portal\Services;
use App\Helpers\InstituteHelper;
use Framework\Http\Request;
use App\Helpers\GroupHelper;

class ImageService
{
    public function getImageWithWatermark(Request $request)
    {
        $entityType = $request['entity_type'];
        $image = $request['image'];

        switch ($entityType) {
            case 'groups':
                $path = $this->getGroupImagePath($image);
                break;
            case 'institutes':
                $path = $this->getInstituteImagePath($image);
                break;
        }
        header('Pragma: public');
        header('Cache-Control: max-age=86400');
        return image_with_watermark($path);

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
