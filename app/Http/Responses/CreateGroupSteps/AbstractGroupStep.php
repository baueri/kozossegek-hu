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

    protected function getModel(): array
    {
        return [];
    }

    public function __toString()
    {
        return $this->render();
    }

    abstract public function render(): string;

    /**
     * Same data as {@see render()} for Mint (or other) templates outside the Blade view path.
     *
     * @return array<string, mixed>
     */
    public function viewData(): array
    {
        return $this->getModel();
    }
}
