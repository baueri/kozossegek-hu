<?php

declare(strict_types=1);

namespace Framework\Middleware;

use Framework\Http\Response;

class JsonApi implements Before
{
    public function before(): void
    {
        Response::asJson();
    }
}
