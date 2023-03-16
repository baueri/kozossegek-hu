<?php

declare(strict_types=1);

namespace App\ThirdParty\Http\Controllers\Api\V1;

use App\Admin\Group\Services\CreateGroup;
use App\Models\ChurchGroup;
use App\Models\ChurchGroupInterface;
use App\Models\ChurchGroupView;
use App\Models\User;
use App\Portal\Services\PortalUpdateGroup;
use App\QueryBuilders\GroupViews;
use App\ThirdParty\Http\JwtPayload;
use DomainException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Controller;
use Framework\Http\Exception\HttpException;
use Framework\Http\Request;
use Framework\Http\Response;

class ChurchGroupController extends Controller
{
    private JwtPayload $payload;

    private User $user;

    /**
     * @throws HttpException
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $jwt = $request->bearerToken();
        if (!$jwt) {
            throw new HttpException('Bad Request');
        }

        try {
            $this->payload = new JwtPayload(
                json_decode(
                    json_encode(JWT::decode($jwt, new Key(_env('API_SITE_KEY'), 'HS256'))),
                    true
                )
            );
        } catch (DomainException $e) {
            throw new HttpException(message: 'Unauthorized', code: 401, previous: $e);
        }

        $this->user = $this->payload->user();
    }

    public function index(GroupViews $groupViews)
    {
        return $groupViews->forUser($this->user)->get()->map->getAttributes();
    }

    public function show(ChurchGroupView $group): array
    {
        $this->checkAccess($group);

        return $group->getAttributes();
    }

    public function store(CreateGroup $service): array
    {
        $group = $service->create(
            $this->request->collect()->set('user_id', $this->user->getId()),
    $this->request->files['document'] ?? []
        );

        if ($service->hasErrors()) {
            Response::setStatusCode(422);
            return $service->getErrors()->all();
        }

        return ['message' => 'Ok', 'group_id' => $group->getId()];
    }

    /**
     * @throws FileTypeNotAllowedException
     */
    public function update(ChurchGroup $group, PortalUpdateGroup $service): array
    {
        $this->checkAccess($group);

        $service->update($group, $this->request->only(
            'status',
            'name',
            'institute_id',
            'age_group',
            'occasion_frequency',
            'on_days',
            'spiritual_movement_id',
            'tags',
            'group_leaders',
            'description',
            'image',
            'join_mode'
        ), $this->request->files['document']);

        return ['message' => 'Success'];
    }

    public function delete(ChurchGroup $group)
    {
        $this->checkAccess($group);

        $group->query()->deleteModel($group);
    }

    private function checkAccess(ChurchGroupInterface $group): void
    {
        if (!$group->isVisibleBy($this->user)) {
            abort(403, 'Forbidden');
        }
    }
}
