<?php

namespace Framework\Mail;

use Framework\Traits\Makeable;

class Mailable
{
    use Makeable;

    /**
     * @var string[]
     */
    public array $from;

    /**
     * @var string
     */
    protected string $view;

    /**
     * @var array
     */
    protected array $viewData = [];

    /**
     * @var string
     */
    public string $subject;


    /**
     * @param  string $view
     * @return static
     */
    final public function view(string $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @param  array  $data
     * @return static
     */
    final public function with(array $data)
    {
        $this->viewData = array_merge($this->viewData, $data);

        return $this;
    }

    /**
     * @param string $from
     * @param string|null $name
     * @return static
     */
    final public function from(string $from, string $name = null)
    {
        $this->from = array_filter([$from, $name]);

        return $this;
    }

    final public function subject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): string
    {
        return view($this->view, $this->viewData);
    }

    /**
     * @return void
     */
    public function build()
    {
    }

    public function getView()
    {
        return $this->view;
    }

    public function getVariableNames()
    {
        return array_keys($this->viewData);
    }
}
