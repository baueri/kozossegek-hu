<?php

namespace App\Http\Responses\CreateGroupSteps;

use App\Enums\Tag;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\SpiritualMovements;
use App\Services\Captcha\Cloudflare\Config;
use Framework\Http\Request;

class FinishRegistration extends RegisterGroupForm
{
    public function __construct(
        Request $request,
        Institutes $institutes,
        SpiritualMovements $spiritualMovements,
        private readonly Config $captchaConfig
    ) {
        parent::__construct($request, $institutes, $spiritualMovements);
    }

    protected function getModel(): array
    {
        $selectedTags = Tag::collect()
            ->filter(fn (Tag $tag) => in_array($tag->value, $this->request['tags']))
            ->map->translate()
            ->implode(', ');

        return array_merge(parent::getModel(), [
            'selected_tags' => $selectedTags,
            'clourflareSiteKey' => $this->captchaConfig->siteKey
        ]);
    }

    protected function getView(): string
    {
        return 'portal.group.create-steps.finish-registration';
    }
}
