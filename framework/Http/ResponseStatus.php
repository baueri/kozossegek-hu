<?php

declare(strict_types=1);

namespace Framework\Http;

use App\Enums\EnumTrait;

enum ResponseStatus: int
{
    use EnumTrait;

    /*
    |--------------------------------------------------------------------------
    | 1XX Informational
    |--------------------------------------------------------------------------
    |
    | The server acknowledges and is processing the request.
    |
    */
    case CONTINUE = 100;
    case SWITCHING_PROTOCOLS = 101;
    case PROCESSING = 102;
    case EARLY_HINTS = 103;

    /*
    |--------------------------------------------------------------------------
    | 2XX Success
    |--------------------------------------------------------------------------
    |
    | The server successfully received, understood, and processed the request.
    |
    */
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NON_AUTHORITATIVE_INFORMATION = 203;
    case NO_CONTENT = 204;
    case RESET_CONTENT = 205;
    case PARTIAL_CONTENT = 206;
    case MULTI_STATUS = 207;
    case ALREADY_REPORTED = 208;
    case IM_USED = 226;

    /*
    |--------------------------------------------------------------------------
    | 3XX Redirection
    |--------------------------------------------------------------------------
    |
    | The server received the request, but there’s a redirect to somewhere else
    | (or, in rare cases, some additional action other than a redirect must be completed).
    |
    */
    case MULTIPLE_CHOICES = 300;
    case MOVED_PERMANENTLY = 301;
    case FOUND = 302;
    case SEE_OTHER = 303;
    case NOT_MODIFIED = 304;
    case TEMPORARY_REDIRECT = 307;
    case PERMANENT_REDIRECT = 308;

    /*
    |--------------------------------------------------------------------------
    | 4XX Client Error
    |--------------------------------------------------------------------------
    |
    | The server couldn’t find (or reach) the page or website. This is an error on the site’s side.
    |
    */
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case PAYMENT_REQUIRED = 402;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case NOT_ALLOWED = 405;
    case NOT_ACCEPTABLE = 406;
    case PROXY_AUTHENTICATION_REQUIRED = 407;
    case REQUEST_TIMEOUT = 408;
    case CONFLICT = 409;
    case GONE = 410;
    case LENGTH_REQUIRED = 411;
    case PRECONDITION_FAILED = 412;
    case CONTENT_TOO_LARGE = 413;
    case REQUEST_URI_TOO_LONG = 414;
    case UNSUPPORTED_MEDIA_TYPE = 415;
    case REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    case EXPECTATION_FAILED = 417;
    case IM_A_TEAPOT = 418;
    case AUTHENTICATION_TIMEOUT = 419;
    case METHOD_FAILURE = 420;
    case INSUFFICIENT_SPACE_ON_RESOURCE = 421;
    case UNPROCESSABLE_ENTITY = 422;
    case LOCKED = 423;
    case FAILED_DEPENDENCY = 424;
    case UPGRADE_REQUIRED = 426;
    case PRECONDITION_REQUIRED = 428;
    case TOO_MANY_REQUESTS = 429;
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    case UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    /*
    |--------------------------------------------------------------------------
    | 5XX Server Error
    |--------------------------------------------------------------------------
    |
    | The client made a valid request, but the server failed to complete the request.
    |
    */
    case INTERNAL_SERVER_ERROR = 500;
    case NOT_IMPLEMENTED = 501;
    case BAD_GATEWAY = 502;
    case SERVICE_UNAVAILABLE = 503;
    case GATEWAY_TIMEOUT = 504;
    case HTTP_VERSION_NOT_SUPPORTED = 505;
    case VARIANT_ALSO_NEGOTIATES = 506;
    case INSUFFICIENT_STORAGE = 507;
    case NOT_EXTENDED = 510;
    case NETWORK_AUTHENTICATION_REQUIRED = 511;

    public static function isValidResponse($statusCode): bool
    {
        return (bool) self::tryFrom((int) $statusCode);
    }
}