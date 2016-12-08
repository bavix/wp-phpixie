<?php

namespace Project\Api\Exceptions;

use Exception;
use Project\Api\ENUM\REST;

class Unauthorized extends \Exception
{
    public function __construct($message = 'Unauthorized', $code = REST::UNAUTHORIZED, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}