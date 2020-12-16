<?php

namespace App\Admin\Controllers;

use Framework\File\File;
use Framework\Http\Request;
use Framework\Storage\PublicStorage;

/**
 * Description of ContentUploadController
 *
 * @author ivan
 */
class ContentUploadController
{
    /**
     *
     * @var PublicStorage
     */
    private $storage;
    
    public function __construct()
    {
        $this->storage = new PublicStorage();
    }
    
   
    public function list(Request $request)
    {
        $dir = $request['dir'];
        
        $breadCrumbs = collect([
            ['name' => 'Feltöltések', 'path' => $this->storage->getDirName()]
        ])->merge(collect(explode('/', $dir))->reverse()->map(function ($dir) {
            return ['name' => basename($dir), 'path' => $dir];
        }));
        
        $files = collect($this->storage->getFiles("uploads/$dir"));
        $uploads = \App\Helpers\FileHelper::parseFilesToArray($files);
        
        return view('admin.uploads.list', compact('uploads', 'breadCrumbs', 'dir'));
    }
    
    public function uploadFile(Request $request)
    {
        try {
            $dir = $request['dir'];
            $file = $this->storage->uploadFile($request->files['file'], null, "uploads/$dir");
            return $this->storage->getPublicPathFor($file);
        } catch (\Exception $e) {
            \Framework\Http\Response::setStatusCode(500);
            return 'Nem lehet feltölteni a fájlt!';
        }
    }
    
    public function deleteFile(Request $request)
    {
        $file = $this->storage->getFileByPubPath($request['file']);
        
        $file->delete();
        
        \Framework\Http\Message::warning('Fájl törölve');
        
        redirect_route('admin.content.upload.list');
    }
}
