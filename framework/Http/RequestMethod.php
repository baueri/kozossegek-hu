<?php

declare(strict_types=1);

namespace Framework\Http;

enum RequestMethod
{
    case GET;
    case POST;
    case PUT;
    case PATCH;
    case DELETE;
}
