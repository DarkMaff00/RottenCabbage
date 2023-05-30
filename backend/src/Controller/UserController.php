<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class UserController extends AbstractController
{

    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHashed;
    private JWTEncoderInterface $JWTEncoder;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHashed, JWTEncoderInterface $JWTEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordHashed = $passwordHashed;
        $this->JWTEncoder = $JWTEncoder;
    }

    /**
     * @throws Exception
     */
    #[Route('/signup', methods: ['POST'])]
    public function signup(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $email = $requestData['email'];
        $password = $requestData['password'];
        $firstName = $requestData['first_name'];
        $lastName = $requestData['last_name'];

        $existingUser = $this->userRepository->findOneByEmail($email);
        if ($existingUser) {
            return new JsonResponse(['message' => 'User already exist. Change your email.'], 409);
        }

        $user = new User();
        $hashedPassword = $this->passwordHashed->hashPassword($user, $password);

        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);

        $this->userRepository->save($user, true);

        return new JsonResponse(['message' => 'User successfully added'], 200);

    }

    #[Route('/following/{uuid}', methods: ['GET'])]
    public function getFollowing(string $uuid): JsonResponse
    {
        $data = [
            'route' => 'following' . $uuid
        ];
        return new JsonResponse($data);
    }


    //Use Case: Filtrowanie Ile ocen sie ma wyswietlac- po jakims czasie najlepsze filmy
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
            'route' => 'user' . $uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/user/{uuid}', methods: ['POST'])]
    public function followUser(string $uuid): JsonResponse
    {
        $data = [
            'route' => 'followUser' . $uuid
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

    /**
     * @param Request $request
     * @param $jwtEncoder
     * @return JsonResponse
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/deleteAccount', methods: ['DELETE'])]
    public function deleteUser(Request $request): JsonResponse
    {
        $bearerToken = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $bearerToken);


        $decodeToken = $this->JWTEncoder->decode($token);
        $userEmail = $decodeToken['username'];
        $data = json_decode($request->getContent(), true);


        $password = $data['password'];
        $user = $this->userRepository->findOneByEmail($userEmail);

        if (!$this->passwordHashed->isPasswordValid($user, $password)) {
            return new JsonResponse(["message" => "Invalid password"], 400);
        }

        $this->userRepository->remove($user, true);

        return new JsonResponse(["message" => "User deleted successfully"]);
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