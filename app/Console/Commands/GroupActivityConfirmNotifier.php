<?php

namespace App\Console\Commands;

use App\Mail\ActiveGroupConfirmEmail;
use App\Models\ChurchGroup;
use App\Models\User;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Users;
use App\QueryBuilders\UserTokens;
use Framework\Console\Command;
use Framework\Model\ModelCollection;

class GroupActivityConfirmNotifier extends Command
{
    public static function signature(): string
    {
        return 'group:notify-groups';
    }

    public function __construct(
        private readonly UserTokens $tokens
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        foreach ($this->getUsers() as $user) {
            $tokens = $user->groups->keyBy('id')->map(function (ChurchGroup $group) use($user) {
                return $this->tokens->createUserToken(
                    user: $user,
                    page: route('confirm_group'),
                    expireDate: now()->addMonth(),
                    data: ['group_id' => $group->getId()]
                );
            });
            $mailable = new ActiveGroupConfirmEmail($user, $tokens);
            $mailable->send($user->email, $user->name);
            ChurchGroups::query()->whereIn('id', $user->groups->map->getId())->update(['notified_at' => now()]);
        }
    }

    /**
     * @return User[]|ModelCollection<User>
     */
    private function getUsers(): ModelCollection
    {
        return Users::query()
            ->with('groups', fn (ChurchGroups $query) => $query->orderBy('created_at')->shouldNotify())
            ->whereHas('groups', fn (ChurchGroups $query) => $query->shouldNotify())
            ->get();
    }
}