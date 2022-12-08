<?php

namespace App\Controller;

use App\Api\OmdbConsumer;
use App\Entity\Movie;
use App\Form\MovieType;
use App\MovieOrderedEvent;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MoviesController extends AbstractController
{
    #[Route('/movies/{slug<[a-zA-Z0-9-]+>}', name: 'app_movies', methods: ['GET'], priority: -100)]
    public function movieDetail(string $slug, MovieRepository $movieRepository, OmdbConsumer $omdbConsumer): Response
    {
        $movie = $movieRepository->findOneBySlug($slug);
        if (null === $movie) {
            throw $this->createNotFoundException();
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

    #[Route('/admin/movies/all', name: 'app_movies_all', methods: ['GET'])]
    public function allMovies(MovieRepository $movieRepository): Response
    {
        return $this->render('movies/all.html.twig', ['movies' => $movieRepository->findAll()]);
    }

    #[Route('/movie/ordered/{slug<[a-zA-Z0-9-]+>}', name: 'app_movie_ordered', methods: ['GET'])]
    public function movieOrdered(Movie $movie, EventDispatcherInterface $eventDispatcher): Response
    {
        $eventDispatcher->dispatch(new MovieOrderedEvent($movie));

        return new Response(sprintf('<body><h1>Movie "%s" ordered !</h1></body>', $movie->getTitle()));
    }
}
