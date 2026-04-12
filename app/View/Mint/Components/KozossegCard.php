<?php

declare(strict_types=1);

namespace App\View\Mint\Components;

use Baueri\Mint\Context;
use Baueri\Mint\Module\Module;

class KozossegCard extends Module
{
    public function render(Context $context): string
    {
        $group = $context->resolve('group');

        if (! is_array($group)) {
            throw new \InvalidArgumentException('mod-kozosseg-card requires :group array.');
        }

        $tags = $group['tags'] ?? [];
        $tags_preview = is_array($tags) && $tags !== [] ? array_slice($tags, 0, 3) : [];
        $extra_tags = is_array($tags) ? count($tags) - count($tags_preview) : 0;
        $show_tags = $tags_preview !== [];
        $show_age = ! empty($group['age_group_combined']);

        return $this->view($context, 'components/kozosseg-card.php', compact(
            'group',
            'tags_preview',
            'extra_tags',
            'show_tags',
            'show_age',
        ));
    }
}
