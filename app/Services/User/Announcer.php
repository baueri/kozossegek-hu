<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\Page;
use App\Models\User;
use App\QueryBuilders\Users;
use Framework\Support\Collection;

class Announcer
{
    /**
     * @param Page|Collection<Page> $page
     * @param Users|User|Collection<User> $users
     */
    public function announce(Page|Collection $page, Users|User|Collection $users): void
    {
        if ($users instanceof User) {
            $users = collect([$users]);
        }

        if ($page instanceof Collection) {
            $page->each(fn (Page $p) => $this->announce($p, $users));
            return;
        }

        $users->each(function (User $user) use ($page) {
            builder('seen_announcements')->insert([
                'user_id' => $user->getId(),
                'announcement_id' => $page->getId(),
            ]);
        });
    }
}
