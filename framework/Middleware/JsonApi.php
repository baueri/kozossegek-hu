<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Framework\Http\Response;

class JsonApi implements Middleware
{
    public function handle(): void
    {
        Response::asJson();
    }
}
