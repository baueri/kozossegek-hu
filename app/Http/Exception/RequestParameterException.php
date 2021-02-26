<?php

namespace App\Http\Exception;

use InvalidArgumentException;
use Throwable;

/**
 * Description of RequestParameterExceptio
 *
 * @author ivan
 */
class RequestParameterException extends InvalidArgumentException
{
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
