<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies/{slug<[a-z0-9-]+>}', name: 'app_movies', methods: ['GET'])]
    public function movieDetail(string $slug, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->findOneBySlug($slug);
        if (null === $movie) {
            throw $this->createNotFoundException();
        }

        return $this->render('movies/index.html.twig', [
            'movie' => $movie,
        ]);
    }
}
