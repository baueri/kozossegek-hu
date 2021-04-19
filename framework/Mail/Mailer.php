<?php

namespace Framework\Mail;

use Framework\Traits\Makeable;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    use Makeable;

    /**
     * @var PHPMailer
     */
    private PHPMailer $phpMailer;

    public function __construct(string $to = '', string $name = '')
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

        if ($to) {
            $this->to($to, $name);
        }
    }

    /**
     * @param string $to
     * @param string|null $name
     * @return $this
     * @throws Exception
     */
    public function to(string $to, string $name = '')
    {
        $this->phpMailer->addAddress($to, $name);

        return $this;
    }

    /**
     * @param Mailable $mailable
     * @return bool
     * @throws Exception
     */
    public function send(Mailable $mailable): bool
    {
        if (app()->envIs('test')) {
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

    private function setMailableForTest(Mailable $mailable)
    {
        $this->phpMailer->setFrom($this->phpMailer->Username, site_name());
        $originalAddressee = key($this->phpMailer->getAllRecipientAddresses());
        $this->phpMailer->clearAddresses();
        $this->phpMailer->addAddress('birkaivan@gmail.com');
        $this->phpMailer->Subject = site_name() . ' (TESZT)';
        $body = $mailable->getBody();
        $this->phpMailer->Body = <<<EOT
            <b style="color: red">FIGYELEM! EZ EGY TESZT VÁLASZÜZENET A LENTI EMAIL-RE.</b><br>
            <b style="color: red">EREDETI CÍMZETT: {$originalAddressee} </b><br>
            {$body}
        EOT;
    }
}
