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
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Controller;
use Framework\Http\Exception\HttpException;
use Framework\Http\Exception\NotFoundException;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\ResponseStatus;
use Framework\Support\Collection;
use UnexpectedValueException;

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
        } catch (ExpiredException $e) {
            header('WWW-Authenticate: Bearer');
            throw new HttpException('Token expired', 401, $e);
        } catch (SignatureInvalidException $e) {
            header('WWW-Authenticate: Bearer');
            throw new HttpException('Invalid token', 401, $e);
        } catch (DomainException|UnexpectedValueException $e) {
            header('WWW-Authenticate: Bearer');
            throw new HttpException('Unauthenticated', 401, $e);
        }

        $this->user = $this->payload->user();
    }

    public function index(GroupViews $groupViews): Collection
    {
        return $groupViews->forUser($this->user)
            ->with('searchEngine')
            ->get()
            ->map(function (ChurchGroupView $group) {
                $data = $group->getAttributes();
                $data['keywords'] = $group->searchEngine['keywords'];
                return $data;
            });
    }

    /**
     * @throws HttpException|NotFoundException
     */
    public function show(ChurchGroupView $group): array
    {
        $this->checkAccess($group);

        return $group->getAttributes();
    }

    /**
     * @throws FileTypeNotAllowedException
     */
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
     * @throws FileTypeNotAllowedException|HttpException|NotFoundException
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

    /**
     * @throws NotFoundException|HttpException
     */
    public function destroy(ChurchGroup $group)
    {
        $this->checkAccess($group);

        $group->query()->deleteModel($group);
    }

    /**
     * @throws NotFoundException|HttpException
     */
    private function checkAccess(ChurchGroupInterface $group): void
    {
        if (!$group->isVisibleBy($this->user)) {
            abort(403, 'Forbidden');
        }
    }
}
