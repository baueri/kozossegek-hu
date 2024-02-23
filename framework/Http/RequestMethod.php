<?php

declare(strict_types=1);

namespace Framework\Http;

use App\Enums\EnumTrait;

enum RequestMethod
{
    use EnumTrait;

    case GET;
    case POST;
    case PUT;
    case PATCH;
    case DELETE;

    public function is(string|array|self $method): bool
    {
        if (is_array($method)) {
            return in_array($this->name, $method);
        }

        if ($method instanceof self) {
            return $method->name === $this->name;
        }

        return $this->name === $method;
    }
}
