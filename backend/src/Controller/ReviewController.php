<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    #[Route('/movieInfo/{uuid}', methods: ['POST'])]
    public function addReview(Request $request, string $uuid): JsonResponse
    {

        $data = [
            'route' => 'addReview'.$uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/movieInfo/{uuid}', methods: ['DELETE'])]
    public function deleteReview(string $uuid): JsonResponse
    {

        $data = [
            'route' => 'deleteReview'.$uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/movieInfo/{uuid}', methods: ['POST'])]
    public function likeReview(string $uuid): JsonResponse
    {

        $data = [
            'route' => 'likeReview'.$uuid
        ];
        return new JsonResponse($data);
    }
}