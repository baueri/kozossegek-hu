<?php

namespace App\Admin\Controllers;

use App\Helpers\FileHelper;
use Exception;
use Framework\File\File;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Storage\PublicStorage;

class ContentUploadController
{
    public function __construct(
        private PublicStorage $storage
    ) {
    }

    public function list(Request $request): string
    {
        $dir = $request['dir'];

        $breadCrumbs = collect([
            ['name' => 'Feltöltések', 'path' => $this->storage->getDirName()]
        ])->merge(collect(explode('/', $dir))->reverse()->map(function ($dir) {
            return ['name' => basename($dir), 'path' => $dir];
        }));

        $files = collect($this->storage->getFiles("uploads/$dir"));
        $uploads = FileHelper::parseFilesToArray($files);

        return view('admin.uploads.list', compact('uploads', 'breadCrumbs', 'dir'));
    }

    public function uploadFile(Request $request): string
    {
        try {
            $dir = $request['dir'];
            $file = $this->storage->uploadFileByFileData($request->files['file'], null, "uploads/$dir");
            return $this->storage->getPublicPathFor($file);
        } catch (Exception $e) {
            Response::setStatusCode(500);
            return 'Nem lehet feltölteni a fájlt!';
        }
    }

    public function deleteFile(Request $request)
    {
        $file = $this->storage->getFileByPubPath($request['file']);

        $file->delete();

        Message::warning('Fájl törölve');

        redirect_route('admin.content.upload.list');
    }
}
