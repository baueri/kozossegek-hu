<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Models\SpiritualMovement;
use App\QueryBuilders\GroupViews;
use App\QueryBuilders\SpiritualMovements;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Request;
use Framework\Model\ModelNotFoundException;

class SpiritualMovementController extends PortalController
{
    public function __construct(
        Request $request,
        private SpiritualMovements $repository
    ) {
        parent::__construct($request);
    }

    public function list(): string
    {
        use_default_header_bg();

        $spiritualMovements = $this->repository
            ->where('highlighted', 1)
            ->orderBy('name')
            ->withCount('groups')
            ->get();

        return view('portal.spiritual_movement.list', compact('spiritualMovements'));
    }

    /**
     * @throws PageNotFoundException
     */
    public function view(GroupViews $groupViews): string
    {
        try {
            /* @var $spiritualMovement SpiritualMovement */
            $spiritualMovement = $this->repository
                ->where('slug', $this->request['slug'])
                ->where('highlighted', 1)
                ->firstOrFail();

            $groups = $groupViews->query()
                ->where('spiritual_movement_id', $spiritualMovement->id)
                ->apply('active')
                ->get();

            $groupids = $groups->getIds();

            if ($groupids->isNotEmpty()) {
                $group_tags = builder('v_group_tags')
                    ->whereIn('group_id', $groupids->all())
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
        } catch (ModelNotFoundException) {
            throw new PageNotFoundException();
        }
    }
}
