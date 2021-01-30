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

    /**
     * @param string $to
     * @param string|null $name
     * @return $this
     * @throws Exception
     */
    public function to(string $to, ?string $name = '')
    {
        $this->phpMailer->addAddress($to, $name);

        return $this;
    }

    /**
     * @param string $to
     * @return $this
     * @throws Exception
     */
    public function cc(string $to)
    {
        $this->phpMailer->addCC($to);

        return $this;
    }

    /**
     * @param Mailable $mailable
     * @return bool
     * @throws Exception
     */
    public function send(Mailable $mailable): bool
    {
        if ($mailable->from) {
            $this->phpMailer->setFrom(...$mailable->from);
        } else {
            $this->phpMailer->setFrom($this->phpMailer->Username, 'kozossegek.hu');
        }
        $this->phpMailer->Subject = $mailable->subject;
        $this->phpMailer->Body = $mailable->getBody();
        return $this->phpMailer->send();
    }
}
