<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    public const MOVIES = [
        'le-torrent' => ['Le torrent', '2022-11-30', ['Thriller'], 'torrent.jpg'],
        'johnny-hallyday' => ['Johnny Hallyday', '2022-12-05', ['Concert'], 'johnny.jpg'],
        'days' => ['Days', '2022-11-30', ['Drama', 'Romance'], 'days.jpg'],
    ];

    #[Route('/movies/{slug<[a-z0-9-]+>}', name: 'app_movies', methods: ['GET'])]
    public function movieDetail(string $slug): Response
    {
        if (!isset(self::MOVIES[$slug])) {
            throw $this->createNotFoundException();
        }

        [$title, $releasedAt, $genres, $poster] = self::MOVIES[$slug];

        return $this->render('movies/index.html.twig', [
            'title' => $title,
            'released_at' => $releasedAt,
            'genres' => $genres,
            'poster' => $poster,
        ]);
    }
}
