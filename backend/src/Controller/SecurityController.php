<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {

        $data = [
            'route' => 'login'
        ];
        return new JsonResponse($data);
    }

    #[Route('/logout')]
    public function logout(): JsonResponse
    {
        $data = [
            'route' => 'logout'
        ];
        return new JsonResponse($data);
    }
}
