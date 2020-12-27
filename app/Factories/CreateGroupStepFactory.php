<?php


namespace App\Factories;


use App\Http\Responses\CreateGroupSteps\AbstractGroupStep;
use App\Http\Responses\CreateGroupSteps\LoginOrRegister;
use App\Http\Responses\CreateGroupSteps\RegisterGroupForm;
use App\Http\Responses\CreateGroupSteps\FinishRegistration;

class CreateGroupStepFactory
{
    /**
     * @param string $step
     * @return AbstractGroupStep
     */
    public function getGroupStep(string $step): AbstractGroupStep
    {
        switch ($step) {
            case 'login':
            default:
                return app()->make(LoginOrRegister::class);
            case 'group_data':
                return app()->make(RegisterGroupForm::class);
            case 'finish_registration':
                return app()->make(FinishRegistration::class);
        }
    }
}
