<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(MovieRepository $repository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findBy([], [], 21),
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_movie_show')]
    public function show(?Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/new', name: 'app_movie_new')]
    #[Route('/{id<\d+>}/edit', name: 'app_movie_edit')]
    public function save(?Movie $movie): Response
    {
        $movie ??= new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        return $this->render('movie/save.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    public function lastMovies(MovieRepository $repository): Response
    {
        $lastMovies = $repository->findBy([], ['releasedAt' => 'DESC'], 10);

        return $this->render('includes/_last_movies.html.twig', [
            'last_movies' => $lastMovies,
        ])->setTtl(3600);
    }
}
