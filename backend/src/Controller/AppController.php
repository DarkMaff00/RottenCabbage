<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/')]
    public function homepage(): JsonResponse
    {

        $data = [
            'route' => 'homepage'
        ];
        return new JsonResponse($data);
    }
}