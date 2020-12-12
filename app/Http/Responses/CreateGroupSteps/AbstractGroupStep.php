<?php

namespace App\Http\Responses\CreateGroupSteps;

abstract class AbstractGroupStep
{

    /**
     * @var \Framework\Http\Request
     */
    protected $request;

    /**
     * @param \Framework\Http\Request $request
     */
    public function __construct(\Framework\Http\Request $request)
    {
        $this->request = $request;
    }
    
    abstract protected function getView();
    
    protected function getModel()
    {
        return [];
    }
    
    public function __toString()
    {
        return view($this->getView(), array_merge(['step' => $this->request['next_step'] ?: 1], $this->getModel()));
    }
}
