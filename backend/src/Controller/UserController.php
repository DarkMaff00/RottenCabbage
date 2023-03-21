<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/signup', methods: ['POST'])]
    public function signup(Request $request): JsonResponse
    {
        $data = [
            'route' => 'signup'
        ];
        return new JsonResponse($data);
    }

    #[Route('/following/{uuid}', methods: ['GET'])]
    public function getFollowing(string $uuid): JsonResponse
    {
        $data = [
            'route' => 'following'.$uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/following', methods: ['GET'])]
    public function getFollowingUsers(): JsonResponse
    {
        $data = [
            'route' => 'followingUsers'
        ];
        return new JsonResponse($data);
    }

    #[Route('/user/{uuid}', methods: ['GET'])]
    public function getUserInfo(string $uuid): JsonResponse
    {
        $data = [
            'route' => 'user'.$uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/user/{uuid}', methods: ['POST'])]
    public function followUser(string $uuid): JsonResponse
    {
        $data = [
            'route' => 'followUser'.$uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/settings')]
    public function showSettings(): JsonResponse
    {
        $data = [
            'route' => 'settings'
        ];
        return new JsonResponse($data);
    }

    #[Route('/deleteAccount', methods: ['DELETE'])]
    public function deleteUser(): JsonResponse
    {
        $data = [
            'route' => 'deleteUser'
        ];
        return new JsonResponse($data);
    }

    #[Route('/changePassword', methods: ['PUT'])]
    public function changePassword(): JsonResponse
    {
        $data = [
            'route' => 'changePassword'
        ];
        return new JsonResponse($data);
    }

}