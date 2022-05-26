<?php

namespace App\SubRequest;

use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClient
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function makeApiRequest(string $url, string $method, string $content = '')
    {
        try {
            $response = $this->client->request($method, $url, [
                'body' => $content
            ]);

            return new JsonResponse($response->getContent(), $response->getStatusCode(), [], true);
        } catch (ServerException $e) {
            return new JsonResponse(['message' => 'API sent back an error'], $e->getResponse()->getStatusCode());
        }
    }
}
