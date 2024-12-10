<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidRequestDataException extends HttpException
{
    public function __construct(string $message = 'Invalid request data', int $statusCode = 400)
    {
        parent::__construct($statusCode, $message);
    }
}
