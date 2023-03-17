<?php

declare(strict_types=1);

namespace App\ThirdParty\Http;

use App\Models\ThirdPartyCredential;
use App\Models\User;
use App\QueryBuilders\ThirdPartyCredentials;
use Carbon\Carbon;

class JwtPayload
{
    private ?User $user = null;

    public function __construct(
        public readonly array $payload
    ) {
    }

    public function expired(): bool
    {
        return Carbon::parse($this->payload['exp'])->isPast();
    }

    public function user(): User
    {
        return $this->user ?? $this->credentials()->user;
    }

    public function context()
    {
        return $this->payload['context'] ?? [];
    }

    private function credentials(): ThirdPartyCredential
    {
        return ThirdPartyCredentials::query()
            ->byCredentials($this->payload['context']['app']['name'], $this->payload['context']['app']['secret'])
            ->with('user')
            ->firstOrFail();
    }
}