<?php

namespace Framework\Mail;

use App\Models\User;

class Mailable
{
    /**
     * @var string[]
     */
    public ?array $from = [];

    protected ?string $view = null;

    protected array $viewData = [];

    public string $subject = '';

    public ?string $message = null;

    public ?string $replyTo = null;

    protected bool $useDefaultTemplate = false;

    final public function view(string $view): self
    {
        $this->view = $view;

        return $this;
    }

    final public function with(array $data): self
    {
        $this->viewData = array_merge($this->viewData, $data);

        return $this;
    }

    final public function from(string $from, string $name = null): self
    {
        $this->from = array_filter([$from, $name]);

        return $this;
    }

    final public function subject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): string
    {
        if ($this->view) {
            return view($this->view, $this->viewData);
        }

        $message = str_replace("\n", "<br/>", $this->message);

        if ($this->useDefaultTemplate) {
            return view('mail.default_template', ['message' => $message]);
        }

        return $message;
    }

    public function getView(): ?string
    {
        return $this->view;
    }

    public function getVariableNames(): array
    {
        return array_keys($this->viewData);
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function usingDefaultTemplate(): self
    {
        $this->useDefaultTemplate = true;

        return $this;
    }

    public function replyTo(string $email): self
    {
        $this->replyTo = $email;

        return $this;
    }

    public function send(string|User $to, ?string $name = null): bool
    {
        if ($to instanceof User) {
            $name ??= $to->name;
            $to = $to->email;
        }

        return (new Mailer($to, $name))->send($this);
    }
}
