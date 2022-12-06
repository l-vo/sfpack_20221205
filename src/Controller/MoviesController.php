<?php

namespace App\Controller;

use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies/{slug<[a-z0-9-]+>}', name: 'app_movies', methods: ['GET'], priority: -100)]
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

    #[Route('/movies/add', name: 'app_movies', methods: ['GET', 'POST'])]
    public function addMovie(): Response
    {
        $form = $this->createForm(MovieType::class);

        return $this->render('movies/add.html.twig', ['form' => $form]);
    }
}
