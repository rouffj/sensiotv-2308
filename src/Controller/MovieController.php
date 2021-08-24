<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Omdb\OmdbClient;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/movie", name="movie_")
 */
class MovieController extends AbstractController
{
    /**
     * @var OmdbClient
     */
    private $omdb;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->omdb = new OmdbClient($httpClient,'28c5b7b1', 'https://www.omdbapi.com');
    }

    /**
     * @Route("/{id}", name="show", requirements={"id": "\d+"})
     */
    public function show($id, MovieRepository $movieRepository): Response
    {
        $movie = $movieRepository->find($id);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie
        ]);
    }


    /**
     * @Route("/{imdbId}/import")
     */
    public function import($imdbId, EntityManagerInterface $entityManager): Response
    {
        $result = $this->omdb->requestById($imdbId);
        $movie = Movie::fromApi($result);

        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
    }

    /**
     * @Route("/latest", name="latest")
     */
    public function latest(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/latest.html.twig', [
            'movies' => $movieRepository->findAll()
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request): Response
    {

        $keyword = $request->query->get('keyword', 'Sky');
        $movies = $this->omdb->requestBySearch($keyword);

        return $this->render('movie/search.html.twig', [
            'keyword' => $keyword,
            'movies' => $movies['Search'],
        ]);
    }
}
