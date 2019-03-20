<?php

namespace App\Exceptions;

use Exception;

class MethodNotAllowedHttpException extends \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
{
    //
    public function __construct(array $allow, string $message = null, \Exception $previous = null, ?int $code = 0, array $headers = [])
    {
        parent::__construct($allow, $message, $previous, $code, $headers);
    }
}
