<?php

namespace App\Helper;

use App\Exception\InvalidRequestDataException;
use Symfony\Component\HttpFoundation\Request;

class RequestHelper
{
    /**
     * Validates and decodes JSON request data.
     *
     * @param Request $request The HTTP request.
     * @return array The decoded request data.
     * @throws InvalidRequestDataException If the JSON data is invalid.
     */
    public function checkRequestData(Request $request): array
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidRequestDataException('Invalid JSON data', 400);
        }
        return $data;
    }
}
