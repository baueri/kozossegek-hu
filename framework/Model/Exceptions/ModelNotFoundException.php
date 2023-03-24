<?php

namespace Framework\Model\Exceptions;

use Throwable;

class ModelNotFoundException extends QueryBuilderException
{
    public function __construct($message = "", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
