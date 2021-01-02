<?php


namespace App\Portal\Controllers;


use App\Portal\Test\TestPipe1;
use App\Portal\Test\TestPipe2;
use App\Services\GroupDocumentUploadService\GoogleDriveGroupDocumentService;
use App\Services\KozossegekGoogleServiceFactory;
use Framework\Support\PipeLine\PipeLine;

use Framework\Mail\Mailer;
use Framework\Mail\Mailable;

class TestController
{
    public function testEmail(KozossegekGoogleServiceFactory $factory)
    {
        $service = new GoogleDriveGroupDocumentService($factory->getService());


    }
}
