<?php

declare(strict_types=1);

namespace App\Admin\Controllers\Api;

use App\Helpers\FileHelper;
use Framework\Http\Request;
use Framework\Storage\PublicStorage;

class UploadController {
    
    public function __invoke(Request $request, PublicStorage $storage): string
    {
        $dir = $request['dir'];
        $folder = root()->storage("public/uploads/{$dir}/");
        $files = $folder->files();

        $breadcrumbs = FileHelper::getBreadCrumb(root()->storage(), $dir);

        $uploads = FileHelper::parseFilesToArray($files);
        FileHelper::sortFiles($uploads);
        
        return view('admin/uploads/api/list', compact('uploads', 'breadcrumbs'));
    }
}
