<?php

namespace App\Helper;

use App\Exception\InvalidRequestDataException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RequestHelperTest extends TestCase
{
    public function testCheckRequestDataValidJson()
    {
        $request = new Request([], [], [], [], [], [], json_encode(['key' => 'value']));
        $requestHelper = new RequestHelper();
        $data = $requestHelper->checkRequestData($request);
        $this->assertEquals(['key' => 'value'], $data);
    }

    public function testCheckRequestDataInvalidJson()
    {
        $this->expectException(InvalidRequestDataException::class);
        $request = new Request([], [], [], [], [], [], 'this is invalid json');
        $requestHelper = new RequestHelper();
        $requestHelper->checkRequestData($request);
    }
}
