<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Security\AccessTokenHandler;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class SecurityController extends AbstractController
{

    private UserPasswordHasherInterface $passwordHashed;
    private JWTTokenManagerInterface $jwtManager;
    private UserRepository $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHashed,
                                JWTTokenManagerInterface    $jwtManager,
                                UserRepository              $userRepository)
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

        if (!$user) {
            return new JsonResponse(['message' => 'No such user exists.'], 401);
        } else if (!$this->passwordHashed->isPasswordValid($user, $password)) {
            return new JsonResponse(['message' => 'Wrong password.'], 401);
        }


        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }

    #[Route('access', methods: ['GET'])]
    public function accessControl(Request $request, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        if (!$user->isIsAdmin()) {
            return new JsonResponse(["message" => "Permission denied"], 403);
        }

        return new JsonResponse(["message" => "Access Granted"], 200);
    }
}
