<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\GroupStatus;
use App\Mail\GroupsInactivated;
use App\Models\User;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Users;
use Framework\Console\Command;
use Framework\Mail\Mailable;
use Framework\Model\ModelCollection;

class InactivateUnconfirmedGroups extends Command
{
    public static function signature(): string
    {
        return 'group:inactivate-unconfirmed';
    }

    public function handle()
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            (new GroupsInactivated($user, $user->grouos))->send($user);
            ChurchGroups::query()->whereIn('id', $user->groups->map->getId())->update([
                'notified_at' => null,
                'status' => GroupStatus::inactive
            ]);
        }
    }

    /**
     * @return ModelCollection<User>
     */
    private function getUsers(): ModelCollection
    {
        return Users::query()
            ->with('groups', fn (ChurchGroups $query) => $query->orderBy('created_at')->shouldInactivate())
            ->whereHas('groups', fn (ChurchGroups $query) => $query->shouldInactivate())
            ->get();
    }
}