<?php

namespace App;

use App\Entity\Movie;
use Symfony\Contracts\EventDispatcher\Event;

final class MovieOrderedEvent extends Event
{
    public function __construct(public readonly Movie $movie)
    {
    }
}