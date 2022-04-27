<?php

namespace App\Mail;

use App\Models\ChurchGroup;
use App\Models\User;
use App\Models\UserToken;
use Framework\Mail\Mailable;
use Framework\Support\Collection;

class ActiveGroupConfirmEmail extends Mailable
{
    /**
     * @param \App\Models\User $maintainer
     * @param \Framework\Support\Collection<UserToken> $tokens
     */
    public function __construct(
        private readonly User $maintainer,
        private readonly Collection $tokens
    ) {
        $this->with([
            'maintainer_name' => $this->maintainer->name,
            'groups_data' => $this->getGroupsData()
        ])
        ->view('email_templates:confirm_active_group')
        ->subject(site_name() . ' - Közösség aktív státuszának megerősítése');
    }

    private function getGroupsData(): array
    {
        return $this->maintainer->groups->map(function (ChurchGroup $group): array {
            return [
                'name' => $group->name,
                'url' => $group->url(),
                'confirm_url' => $this->tokens->get($group->getId())->getUrlWithEncodedParams(['group_id' => $group->getId(), 'action' => 'confirm']),
                'deactivate_url' => $this->tokens->get($group->getId())->getUrlWithEncodedParams(['group_id' => $group->getId(), 'action' => 'deactivate']),
            ];
        })->all();
    }
}
