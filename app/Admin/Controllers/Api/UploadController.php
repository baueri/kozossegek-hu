<?php

namespace App\Admin\Controllers\Api;

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
        
        $uploads = \App\Helpers\FileHelper::parseFilesToArray($files);
        
        return view('admin.uploads.api.list', compact('uploads'));
    }
}
