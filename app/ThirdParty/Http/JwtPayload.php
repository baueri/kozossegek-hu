<?php

declare(strict_types=1);

namespace App\ThirdParty\Http;

use App\Models\User;
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
        return $this->user ?? User::query()
            ->findOrFail($this->context()['user']['id']);
    }

    public function context()
    {
        return $this->payload['context'] ?? [];
    }
}