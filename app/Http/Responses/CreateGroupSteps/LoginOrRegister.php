<?php
namespace App\Http\Responses\CreateGroupSteps;

class LoginOrRegister extends AbstractGroupStep
{

    protected function getView() {
        return 'portal.group.create-steps.step-reg';
    }

}
