<?php


namespace App\Admin\Components\DebugBar;


class ErrorTab extends DebugBarTab
{
    protected $errors = [];

    public function getName()
    {
        return 'hibák';
    }

    public function pushError($error)
    {
        $this->errors[] = $error;
    }

    public function render()
    {
        return $this->errors ? implode('<br>', $this->errors) : ' - ';
    }
}