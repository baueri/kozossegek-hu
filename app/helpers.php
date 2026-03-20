<?php

use App\Auth\Auth;
use App\Models\User;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

function social_provider_enabled(): bool
{
    return env('GOOGLE_LOGIN_ENABLED') && !(new CrawlerDetect())->isCrawler();
}

function auth(): ?User
{
    return Auth::user();
}
