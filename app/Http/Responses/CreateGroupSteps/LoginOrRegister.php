<?php
namespace App\Http\Responses\CreateGroupSteps;

use Framework\Http\Request;
use Framework\Http\Session;

class LoginOrRegister extends AbstractGroupStep
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    protected function getModel()
    {
        $data = Session::get(static::SESSION_KEY, []);

        return $data;
    }

    protected function getView()
    {
        return 'portal.group.create-steps.step-reg';
    }
}
