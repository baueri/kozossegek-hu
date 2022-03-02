<?php

namespace App\Http\Responses\CreateGroupSteps;

class FinishRegistration extends RegisterGroupForm
{
    protected function getModel(): array
    {
        return array_merge(parent::getModel(), [
            'selected_tags' => collect(builder('tags')->get())
                ->filter(fn ($tag) => in_array($tag['slug'], $this->request['tags']))
                ->map(fn ($tag) => $tag['tag'])->implode(', ')
        ]);
    }

    protected function getView(): string
    {
        return 'portal.group.create-steps.finish-registration';
    }
}
