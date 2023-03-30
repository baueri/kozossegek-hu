<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model\Entity;
use Carbon\Carbon;
use Framework\Model\Model;

/**
 * @property $token
 * @property $email
 * @property $page
 * @property $expires_at
 */
class UserToken extends Entity
{
    public function getUrl(): string
    {
        return "{$this->page}?token={$this->token}";
    }


    public function getUrlWithEncodedParams(array $additionalQueryParams = [])
    {
        $query = 'verify=' . base64_encode($this->buildQuery($additionalQueryParams));
        return "{$this->page}?{$query}";
    }

    public function expired(): bool
    {
        return Carbon::parse($this->expires_at)->isPast();
    }

    public function data(string $key)
    {
        if (!$this->data) {
            return null;
        }
        return json_decode($this->data)->{$key} ?? null;
    }

    private function buildQuery(array $additionalQueryParams = []): string
    {
        return collect(['token' => $this->token])->merge($additionalQueryParams)->buildQuery();
    }
}