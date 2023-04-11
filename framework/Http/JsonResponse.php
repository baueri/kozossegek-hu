<?php

declare(strict_types=1);

namespace Framework\Http;

abstract class JsonResponse
{
    abstract public function response();

    public function __toString(): string
    {
        return json_encode($this->response(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}