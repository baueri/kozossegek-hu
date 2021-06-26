<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Helpers\SpiritualMovementHelper;
use App\Repositories\GroupViews;
use App\Repositories\SpiritualMovements;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Request;
use Framework\Model\ModelNotFoundException;

class SpiritualMovementController extends PortalController
{
    private SpiritualMovements $repository;

    public function __construct(Request $request, SpiritualMovements $repository)
    {
        parent::__construct($request);
        $this->repository = $repository;
    }

    public function list()
    {
        use_default_header_bg();
        $spiritualMovements = $this->repository->query()
            ->where('highlighted', 1)
            ->orderBy('name')->get();

        SpiritualMovementHelper::loadGroupsCount($spiritualMovements);

        return view('portal.spiritual_movement.list', compact('spiritualMovements'));
    }

    public function view()
    {
        try {
            /* @var $spiritualMovement \App\Models\SpiritualMovement */
            $spiritualMovement = $this->repository->query()
                ->where('slug', $this->request['slug'])
                ->where('highlighted', 1)
                ->firstOrFail();

            $groups = app(GroupViews::class)->query()
                ->where('spiritual_movement_id', $spiritualMovement->id)
                ->apply('active')
                ->get();

            $groupids = $groups->pluck('id');

            if ($groupids->isNotEmpty()) {
                $group_tags = builder('v_group_tags')
                    ->whereIn('group_id', $groupids->toArray())
                    ->get();

                if ($group_tags) {
                    $groups->withMany($group_tags, 'tags', 'id', 'group_id');
                }
            }
            $title = $spiritualMovement->name;

            if (Auth::loggedIn()) {
                $editUrl = route('admin.spiritual_movement.edit', $spiritualMovement);
                $title .= " <a href='{$editUrl}' target='_blank' class='edit-page' title='Szerkesztés'><i class='fa fa-pencil-alt'></i> </a>";
            }

            use_default_header_bg();
            return view('portal.spiritual_movement.view', compact('spiritualMovement', 'groups', 'title'));
        } catch (ModelNotFoundException $e) {
            throw new PageNotFoundException();
        }
    }
}
