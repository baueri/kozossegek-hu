<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Responses\CreateGroupSteps;

/**
 * Description of UploadDocument
 *
 * @author ivan
 */
class UploadDocument extends AbstractGroupStep {
    
    //put your code here
    protected function getView() {
        return 'portal.group.create-steps.upload-document';
    }

}
