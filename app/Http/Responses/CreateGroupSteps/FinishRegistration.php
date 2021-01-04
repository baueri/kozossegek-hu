<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Responses\CreateGroupSteps;

use App\Models\GroupView;

/**
 * Description of UploadDocument
 *
 * @author ivan
 */
class FinishRegistration extends RegisterGroupForm
{
    protected function getModel()
    {
        return array_merge(parent::getModel(), [
            'selected_tags' => collect(builder('tags')->get())->filter(fn($tag) => in_array($tag['slug'], $this->request['tags']))->map(fn($tag) => $tag['tag'])->implode(', ')]);
    }

    protected function getView()
    {
        return 'portal.group.create-steps.finish-registration';
    }

}
