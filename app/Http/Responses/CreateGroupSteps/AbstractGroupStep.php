<?php

namespace App\Http\Responses\CreateGroupSteps;

use Framework\Http\Request;

abstract class AbstractGroupStep
{
    public const SESSION_KEY = 'create_group_data';

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

    abstract protected function getView(): string;

    protected function getModel(): array
    {
        return [];
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render(): string
    {
        return view($this->getView(), $this->getModel());
    }
}
