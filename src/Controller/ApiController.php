<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/movies", name="api_movies")
     */
    public function index(): Response
    {
        $movies = [
            ['title' => 'OSS 117 au caire'],
            ['title' => 'Harry Potter'],
        ];

        return new JsonResponse($movies);
        //return $this->json($movies);
    }
}
