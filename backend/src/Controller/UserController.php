<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\AccessTokenHandler;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;


class UserController extends AbstractController
{

    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHashed;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHashed)
    {
        $this->userRepository = $userRepository;
        $this->passwordHashed = $passwordHashed;
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

    #[Route('/users', methods: ['GET'])]
    public function getUsers(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('search');

        $users = $this->userRepository->searchUsersByEmail($searchTerm);

        $response = [];
        foreach ($users as $user) {
            $response[] = [
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
            ];
        }

        return new JsonResponse($response);

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

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/{email}', methods: ['GET'])]
    public function getUserId(string $email): JsonResponse
    {
        $user = $this->userRepository->findOneByEmail($email);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 400);
        }

        $id = $user->getId();

        return new JsonResponse($id);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/user/{uuid}', methods: ['GET'])]
    public function getUserInfo(string $uuid): JsonResponse
    {
        $user = $this->userRepository->findOneById($uuid);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 400);
        }

        $data = [
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
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

    /**
     * @param Request $request
     * @param AccessTokenHandler $accessTokenHandler
     * @return JsonResponse
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/deleteAccount', methods: ['DELETE'])]
    public function deleteUser(Request $request, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $data = json_decode($request->getContent(), true);
        $password = $data['password'];

        if (!$this->passwordHashed->isPasswordValid($user, $password)) {
            return new JsonResponse(["message" => "Invalid password"], 400);
        }

        $this->userRepository->remove($user, true);

        return new JsonResponse(["message" => "User deleted successfully"]);
    }

    #[Route('/changePassword', methods: ['PUT'])]
    public function changePassword(Request $request, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $data = json_decode($request->getContent(), true);
        $password = $data['password'];
        $newPassword = $data['new_password'];

        if (!$this->passwordHashed->isPasswordValid($user, $password)) {
            return new JsonResponse(["message" => "Invalid password"], 400);
        }

        $hashedPassword = $this->passwordHashed->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user, $newPassword);

        return new JsonResponse(["message" => "Password changed successfully"]);
    }

}