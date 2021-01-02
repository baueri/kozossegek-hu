<?php

namespace App\Services\GroupDocumentUploadService;

use App\Services\KozossegekGoogleServiceFactory;
use Framework\File\File;
use Google_Service_Drive;

class GoogleDriveGroupDocumentService
{
    private Google_Service_Drive $service;

    public function __construct(Google_Service_Drive $service)
    {
        $this->service = $service;
    }

    public function uploadDocument(File $file)
    {
        $this->service->files->create();
    }
}
