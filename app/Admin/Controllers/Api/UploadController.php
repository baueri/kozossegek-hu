<?php

namespace App\Admin\Controllers\Api;

use App\Helpers\FileHelper;
use Framework\Http\Request;
use Framework\Storage\PublicStorage;

/**
 * Description of UploadController
 *
 * @author ivan
 */
class UploadController {
    
    public function getUploads(Request $request, PublicStorage $storage)
    {
        $dir = $request['dir'];
        
        $files = collect($storage->getFiles("uploads/$dir"));
        
        $uploads = FileHelper::parseFilesToArray($files);
        
        return view('admin.uploads.api.list', compact('uploads'));
    }
}
