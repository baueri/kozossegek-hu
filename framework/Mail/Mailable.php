<?php

namespace Framework\Mail;

class Mailable
{
    /**
     * @var string
     */
    public $to;

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    protected $view;

    /**
     * @var array
     */
    protected $viewData = [];

    /**
     * @var string
     */
    public $subject;


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
        $this->viewData = $data;

        return $this;
    }

    /**
     * @param  string $from
     * @return static
     */
    final public function from(string $from)
    {
        $this->from = $from;

        return $this;
    }

    final public function subject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody():string
    {
        return view($this->view, $this->viewData);
    }

    /**
     * @return void
     */
    public function build(){}

}