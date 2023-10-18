<?php

declare(strict_types=1);

namespace App\Admin\Controllers\Api;

use App\Helpers\FileHelper;
use Framework\Http\Request;
use Framework\Storage\PublicStorage;

class UploadController {
    
    public function getUploads(Request $request, PublicStorage $storage): string
    {
        $dir = $request['dir'];
        
        $files = collect($storage->getFiles("uploads/$dir"));
        
        $uploads = FileHelper::parseFilesToArray($files);
        
        return view('admin.uploads.api.list', compact('uploads'));
    }
}
