<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\GroupStatus;
use App\Mail\GroupsInactivated;
use App\Models\User;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Users;
use Framework\Console\Command;
use Framework\Model\ModelCollection;

class InactivateUnconfirmedGroups extends Command
{
    public static function signature(): string
    {
        return 'group:inactivate-unconfirmed';
    }

    public static function description(): string
    {
        return sprintf('Inaktiválja azokat a közösségeket, amelyek %d hónappal a kiküldött emlékeztető email után se lettek megerősítve.', ChurchGroups::GROUP_INACTIVATE_AFTER_NOTIFICATION);
    }

    public function handle()
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            ChurchGroups::query()->whereIn('id', $user->groups->map->getId())->update([
                'notified_at' => null,
                'status' => GroupStatus::inactive
            ]);
            (new GroupsInactivated($user, $user->groups))->send($user);
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
