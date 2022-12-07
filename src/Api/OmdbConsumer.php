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

    public function get(string $movieId): MovieDto
    {
        $response = $this->omdbClient->request('GET', '/', ['query' => ['i' => $movieId]]);
        $data = $response->toArray();

        if (!isset($data['Title'])) {
            throw new ClientException($response);
        }

        return new MovieDto(
            $data['Title'],
            $data['Poster'],
            $data['Country'],
            explode(', ', $data['Genre']),
            new \DateTimeImmutable($data['Released']),
        );
    }
}