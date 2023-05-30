<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHashed;
    private JWTTokenManagerInterface $jwtManager;

    private UserRepository $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHashed, JWTTokenManagerInterface $jwtManager, UserRepository $userRepository)
    {
        $this->passwordHashed = $passwordHashed;
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Exception
     */
    #[Route('api/login_check', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userRepository->findOneByEmail($email);

        if (!$user || !$this->passwordHashed->isPasswordValid($user, $password)) {
            return new JsonResponse(['message' => 'Invalid credentials.'], 401);
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
