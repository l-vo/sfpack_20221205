<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class NavBarController extends AbstractController
{
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('nav_bar/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }
}
