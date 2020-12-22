<?php

namespace App\Http\Responses\CreateGroupSteps;

use Framework\Http\Request;

abstract class AbstractGroupStep
{
    const SESSION_KEY = 'create_group_data';

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    abstract protected function getView();
    
    protected function getModel()
    {
        return [];
    }

    public function render(array $data): string
    {
        return view($this->getView(), array_merge($data, $this->getModel()));
    }
}
