<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidResponseDataException extends HttpException
{
    public function __construct(string $message = 'Invalid response data', int $statusCode = 400)
    {
        parent::__construct($statusCode, $message);
    }
}
