<?php

namespace App\Portal\Services;
use App\Helpers\InstituteHelper;
use Framework\Http\Request;

class ImageService
{
    public function getImageWithWatermark(Request $request)
    {
        $entityType = $request['entity_type'];
        $image = $request['image'];

        switch ($entityType) {
            case 'groups':
                $path = $this->getGroupImagePath($image);
            case 'institutes':
                $path = $this->getInstituteImagePath($image);
        }

        return \image_with_watermark($path);

    }

    private function getGroupImagePath($image)
    {
        [$groupId] = explode('_', $image);
        return App\Helpers\GroupHelper::getStoragePath($groupId) . '.jpg';
    }

    private function getInstituteImagePath($image)
    {
        [,$instituteId] = explode('inst_', $image);
        return InstituteHelper::getImageStoragePath($instituteId);
    }
}
