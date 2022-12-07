<?php

namespace App\Api;

use App\Dto\MovieDto;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class OmdbConsumer
{
    public function __construct(private HttpClientInterface $omdbClient)
    {
    }

    public function getById(string $movieId): array
    {
        return $this->getByCriteria('i', $movieId);
    }

    public function getByTitle(string $movieTitle): array
    {
        return $this->getByCriteria('t', $movieTitle);
    }

    private function getByCriteria(string $criteria, string $value): array
    {
        $response = $this->omdbClient->request('GET', '/', ['query' => [$criteria => $value]]);
        $data = $response->toArray();

        if (!isset($data['Title'])) {
            throw new ClientException($response);
        }

        return $data;
    }
}