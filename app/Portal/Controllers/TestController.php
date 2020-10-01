<?php


namespace App\Portal\Controllers;


use App\Portal\Test\TestPipe1;
use App\Portal\Test\TestPipe2;
use Framework\Support\PipeLine\PipeLine;

use Framework\Mail\Mailer;
use Framework\Mail\Mailable;

class TestController
{
    public function testEmail(Mailer $mailer)
    {
        $mail = (new Mailable())->subject('Még egy teszt')->view('mail.test_email');
        echo $mail->getBody();
    }
}
