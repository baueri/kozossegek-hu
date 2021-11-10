<?php

namespace App\Middleware;

use App\EventListeners\LoadViewToDebugBar;
use Framework\Http\View\ViewLoaded;
use Framework\Middleware\Middleware;

class ListenViewLoading implements Middleware
{

    public function handle(): void
    {
        ViewLoaded::listen(LoadViewToDebugBar::class);
//        ModelUpdated::listen(function (ModelUpdated $event) {
//            if ($event->model instanceof Group || is_subclass_of($event->model, Group::class)) {
//                $instituteId = $event->model->institute_id;
//                db()->execute(
//                    'update institutes set group_count=(select count(*) from church_groups where institute_id=? and church_groups.deleted_at is null) where id=?',
//                    $instituteId,
//                    $instituteId
//                );
//            }
//        });
    }
}
