<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'movies' => $movieRepository->findLatestMovies(),
        ]);
    }

    #[Route('/admin/home', name: 'app_home_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function homeAdmin(MovieRepository $movieRepository): Response
    {
        return new Response('<body><h1>Admin homepage</h1></body>');
    }
}
