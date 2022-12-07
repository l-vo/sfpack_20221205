<?php

namespace App\Dto;

final class MovieDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $poster,
        public readonly string $country,
        public readonly array $genreNames,
        public readonly \DateTimeImmutable $released,
    )
    {
    }
}