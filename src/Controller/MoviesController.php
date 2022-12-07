<?php

namespace App\Controller;

use App\Api\OmdbConsumer;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    #[Route('/movies/{slug<[a-z0-9-]+>}', name: 'app_movies', methods: ['GET'], priority: -100)]
    public function movieDetail(string $slug, MovieRepository $movieRepository, OmdbConsumer $omdbConsumer): Response
    {
        try {
            $movie = $omdbConsumer->get($slug);
        } catch (ClientException) {
            $movie = $movieRepository->findOneBySlug($slug);
            if (null === $movie) {
                throw $this->createNotFoundException();
            }
        }

        return $this->render('movies/index.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/movies/add', name: 'app_movies_add', methods: ['GET', 'POST'])]
    #[Route('/movies/edit/{slug}', name: 'app_movies_edit', methods: ['GET', 'POST'])]
    public function addMovie(Request $request, MovieRepository $movieRepository, ?Movie $movie): Response
    {
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->save($form->getData(), true);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('movies/add.html.twig', ['form' => $form]);
    }
}
