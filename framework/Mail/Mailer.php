<?php

namespace Framework\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{

    /**
     * @var PHPMailer
     */
    private $phpMailer;

    /**
     * @param PHPMailer $phpMailer
     */
    public function __construct()
    {
        $phpMailer = new PHPMailer(true);
        $phpMailer->isSMTP();
        $phpMailer->Host = config('app.email_host');
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = config('app.email');
        $phpMailer->Password = config('app.email_password');
        $phpMailer->SMTPSecure = config('app.email_ssl');
        $phpMailer->Port = config('app.email_port');
        $phpMailer->isHTML(true);
        $phpMailer->CharSet = 'UTF-8';
        $this->phpMailer = $phpMailer;
    }

    public function to(string $to)
    {
        $this->phpMailer->addAddress($to);

        return $this;
    }

    public function send(Mailable $mailable)
    {
        if ($mailable->from) {
            $this->phpMailer->setFrom(...$mailable->from);
        } else {
            $this->phpMailer->setFrom($this->phpMailer->Username, 'kozossegek.hu');
        }
        $this->phpMailer->Subject = $mailable->subject;
        $this->phpMailer->Body = $mailable->getBody();
        $this->phpMailer->send();
    }
}
