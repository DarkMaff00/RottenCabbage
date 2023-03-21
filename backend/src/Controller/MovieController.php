<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/ranking', methods: ['GET'])]
    public function getMovies(): JsonResponse
    {

        $data = [
            'route' => 'movies'
        ];
        return new JsonResponse($data);
    }

    #[Route('/ranking/series', methods: ['GET'])]
    public function getSeries(): JsonResponse
    {

        $data = [
            'route' => 'series'
        ];
        return new JsonResponse($data);
    }

    #[Route('/premiers', methods: ['GET'])]
    public function getPremiers(): JsonResponse
    {

        $data = [
            'route' => 'premiers'
        ];
        return new JsonResponse($data);
    }

    #[Route('/addMovie', methods: ['POST'])]
    public function addMovie(Request $request): JsonResponse
    {

        $data = [
            'route' => 'addMovie'
        ];
        return new JsonResponse($data);
    }

    #[Route('/movieInfo/{uuid}', methods: ['GET'])]
    public function movieInfo(string $uuid): JsonResponse
    {

        $data = [
            'route' => 'movieInfo'.$uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/movieInfo/{uuid}', methods: ['POST'])]
    public function rateMovie(Request $request, string $uuid): JsonResponse
    {

        $data = [
            'route' => 'rateMovie'.$uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/movieInfo/{uuid}', methods: ['POST'])]
    public function favouriteMovie(string $uuid): JsonResponse
    {

        $data = [
            'route' => 'favouriteMovie'.$uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/movieInfo/{uuid}', methods: ['POST'])]
    public function wantSeeMovie(string $uuid): JsonResponse
    {

        $data = [
            'route' => 'wantSeeMovie'.$uuid
        ];
        return new JsonResponse($data);
    }
}