<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class HttpResponseException extends \Illuminate\Http\Exceptions\HttpResponseException
{
    public function __construct(Response $response)
    {
        parent::__construct($response);
    }
}
