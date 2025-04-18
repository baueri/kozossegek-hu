<?php

namespace Framework\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $phpMailer;

    public function __construct(string $address = '', ?string $name = '')
    {
        $phpMailer = new PHPMailer(true);
        $phpMailer->isSMTP();
        $phpMailer->Host = config('app.email_host');
        $phpMailer->SMTPAuth = true;
        $phpMailer->Username = config('app.email');
        $phpMailer->Password = config('app.email_password');
        $phpMailer->SMTPSecure = config('app.email_ssl');
        $phpMailer->Port = config('app.email_port');
        $phpMailer->isHTML();
        $phpMailer->CharSet = 'UTF-8';
        $this->phpMailer = $phpMailer;

        if ($address) {
            $this->to($address, $name);
        }
    }

    /**
     * @throws Exception
     */
    public function to(string $address, ?string $name = ''): self
    {
        $this->phpMailer->addAddress($address, (string) $name);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(Mailable $mailable): bool
    {
        if (!is_prod()) {
            $this->setMailableForTest($mailable);
        } else {
            if ($mailable->from) {
                $this->phpMailer->setFrom(...$mailable->from);
            } else {
                $this->phpMailer->setFrom($this->phpMailer->Username, site_name());
            }
            $this->phpMailer->Subject = $mailable->subject;
            $this->phpMailer->Body = $mailable->getBody();
        }

        if ($mailable->replyTo) {
            $this->phpMailer->addReplyTo($mailable->replyTo);
        }

        return $this->phpMailer->send();
    }

    /**
     * @throws Exception
     */
    private function setMailableForTest(Mailable $mailable): void
    {
        $this->phpMailer->setFrom($this->phpMailer->Username, site_name());
        $originalAddressee = key($this->phpMailer->getAllRecipientAddresses());
        $this->phpMailer->clearAddresses();
        $this->phpMailer->addAddress('ivan.bauer90@gmail.com');
        $this->phpMailer->Subject = site_name() . ' (TESZT)';
        $body = $mailable->getBody();
        $this->phpMailer->Body = <<<EOT
            <b style="color: red">FIGYELEM! EZ EGY TESZT VÁLASZÜZENET A LENTI EMAIL-RE.</b><br>
            <b style="color: red">EREDETI CÍMZETT: {$originalAddressee} </b><br>
            {$body}
        EOT;
    }
}
