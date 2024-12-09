<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FakeStoreApiService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getProducts(): array
    {
        try {
            $response = $this->client->request('GET', 'https://fakestoreapi.com/products');
            if ($response->getStatusCode() !== 200) {
                return [];
            }
        } catch (TransportExceptionInterface $e) {
            return [];
        }
        return $response->toArray();
    }
}
