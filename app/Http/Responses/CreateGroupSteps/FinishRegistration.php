<?php

namespace App\Http\Responses\CreateGroupSteps;

use App\Enums\Tag;

class FinishRegistration extends RegisterGroupForm
{
    protected function getModel(): array
    {
        $selectedTags = Tag::collect()
            ->filter(fn (Tag $tag) => in_array($tag->value, $this->request['tags']))
            ->map->translate()
            ->implode(', ');

        return array_merge(parent::getModel(), [
            'selected_tags' => $selectedTags
        ]);
    }

    protected function getView(): string
    {
        return 'portal.group.create-steps.finish-registration';
    }
}
