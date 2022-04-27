<?php

namespace App\Models;

use Carbon\Carbon;
use Framework\Model\Model;

class UserToken extends Model
{
    public $id;

    public $token;

    public $email;

    public $page;

    public $expires_at;

    public $data;

    public function getUrl(array $additionalQueryParams = []): string
    {
        return "{$this->page}?{$this->buildQuery($additionalQueryParams)}";
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