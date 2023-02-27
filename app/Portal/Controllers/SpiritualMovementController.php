<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Enums\SpiritualMovementType;
use App\Models\SpiritualMovement;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\GroupViews;
use App\QueryBuilders\SpiritualMovements;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Request;
use Framework\Http\View\Section;
use Framework\Model\Exceptions\ModelNotFoundException;

class SpiritualMovementController extends PortalController
{
    protected SpiritualMovementType $type = SpiritualMovementType::spiritual_movement;

    protected string $title = 'Lelkiségi mozgalmak';

    protected string $description = <<<HTML
        <p>
            Egy katolikus lelkiségi mozgalom olyan közösség, amely a római katolikus egyház tanítását követve, a szerzetesrendek és a különféle társulatok, egyesületek mellett újabb alternatívát kínálnak a katolikus hit megélésének területén.
        </p>
        <p>
            A mai idők hasonló új kezdeményezéseit általában „mozgalmak és lelkiségek” néven foglaljuk össze. A régebbi kezdeményezések az idők során jórészt intézményesültek, alkalmazkodtak a plébániarendszer által meghatározott szervezeti struktúrához. Egyes nagy múltra visszatekintő szervezetek eredeti lendületüket fölelevenítve maguk is az újonnan induló áramlatok mellé sorakoznak fel.
        </p>
    HTML;


    public function __construct(
        Request $request,
        private readonly SpiritualMovements $repository
    ) {
        parent::__construct($request);
    }

    public function list(): string
    {
        use_default_header_bg();

        $spiritualMovements = $this->repository
            ->where('type', $this->type->name)
            ->hightLighted()
            ->orderBy('name')
            ->withCount('groups', fn(ChurchGroups $query) => $query->active())
            ->get();

        $title = $this->title;
        $description = $this->description;

        return view(
            'portal.spiritual_movement.list',
            compact('spiritualMovements', 'title', 'description')
        );
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
                ->hightLighted()
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
