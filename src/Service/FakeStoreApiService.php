<?php

namespace App\Service;

use App\Exception\InvalidResponseDataException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FakeStoreApiService
{
    private const API_URL = 'https://fakestoreapi.com/products';
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Fetch products from remote API
     *
     * @return array
     */
    public function getProducts(): array
    {
        try {
            $response = $this->client->request('GET', $this::API_URL);
            if ($response->getStatusCode() !== 200) {
                throw new InvalidResponseDataException('Invalid response status code from remote API: ' . $response->getStatusCode());
            }
        } catch (TransportExceptionInterface $e) {
            throw new InvalidResponseDataException('Error while fetching data from remote API: ' . $e->getMessage());
        }
        return $response->toArray();
    }
}
