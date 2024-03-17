<?php

namespace App\Providers;

use App\Auth\Auth;
use App\QueryBuilders\Pages;
use Framework\Database\Builder;
use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class AppServiceProvider implements Middleware
{
    public function handle(): void
    {
        View::setVariable('is_home', is_home());
        View::setVariable('is_prod', is_prod());
        View::setVariable('header_background', '');

        $announcements = null;
        if (Auth::user()) {
            $announcements = Pages::query()
                ->announements()
                ->notDeleted()
                ->published()
                ->whereHas('seenAnnouncements', function (Builder $query) {
                    $query->where('user_id', Auth::user()->id)
                        ->whereNull('seen_at');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        }
        View::setVariable('announcements', $announcements);
    }
}
