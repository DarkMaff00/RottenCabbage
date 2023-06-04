<?php

namespace App\Controller;

use App\Entity\Favourite;
use App\Entity\Rate;
use App\Entity\User;
use App\Repository\FavouriteRepository;
use App\Repository\MovieRepository;
use App\Repository\RateRepository;
use App\Repository\ReviewRepository;
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
use function Webmozart\Assert\Tests\StaticAnalysis\contains;


class UserController extends AbstractController
{

    private UserRepository $userRepository;
    private MovieRepository $movieRepository;

    private RateRepository $rateRepository;

    private FavouriteRepository $favouriteRepository;
    private UserPasswordHasherInterface $passwordHashed;
    private ReviewRepository $reviewRepository;

    public function __construct(UserRepository              $userRepository,
                                UserPasswordHasherInterface $passwordHashed,
                                MovieRepository             $movieRepository,
                                FavouriteRepository         $favouriteRepository,
                                RateRepository              $rateRepository,
                                ReviewRepository            $reviewRepository)
    {
        $this->userRepository = $userRepository;
        $this->passwordHashed = $passwordHashed;
        $this->movieRepository = $movieRepository;
        $this->favouriteRepository = $favouriteRepository;
        $this->rateRepository = $rateRepository;
        $this->reviewRepository = $reviewRepository;
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
     * @throws NonUniqueResultException|JWTDecodeFailureException
     */
    #[Route('/user/{uuid}', methods: ['GET'])]
    public function getUserInfo(string $uuid, Request $request, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        $follows = false;
        try {
            $mainUser = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }


        $user = $this->userRepository->findOneById($uuid);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 400);
        }

        if (in_array($uuid, $mainUser->getFollowing())) {
            $follows = true;
        }


        $data = [
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'follows' => $follows,
        ];

        return new JsonResponse($data);
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/follow/{uuid}', methods: ['POST'])]
    public function followUser(string $uuid, Request $request, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $following = $this->userRepository->findOneById($uuid);
        $user->addFollowing($following);
        $this->userRepository->followUser($user, $following);

        return new JsonResponse(['message' => 'User followed successfully']);
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/unfollow/{uuid}', methods: ['POST'])]
    public function unfollowUser(string $uuid, Request $request, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $following = $this->userRepository->findOneById($uuid);
        $user->removeFollowing($following);
        $this->userRepository->unfollowUser($user, $following);

        return new JsonResponse(['message' => 'User unfollowed successfully']);
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

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/addFavourite/{movieId}', methods: ['POST'])]
    public function favouriteMovie(string $movieId, Request $request, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $data = json_decode($request->getContent(), true);
        $isFavourite = $data['isFavourite'];

        $movie = $this->movieRepository->findOneById($movieId);

        $favourite = new Favourite();
        $favourite->setIsFavourite($isFavourite);
        $favourite->setUserName($user);
        $favourite->setMovie($movie);

        foreach ($user->getSpecials()->getValues() as $fav) {
            if ($favourite->equals($fav)) {
                $this->favouriteRepository->remove($fav, true);
                $user->removeSpecial($fav);
                return new JsonResponse(["message" => "Removed from favourite"]);
            }
        }

        $user->addSpecial($favourite);
        $this->favouriteRepository->save($favourite, true);
        return new JsonResponse(["message" => "Added to favourite"]);
    }

    #[Route('/checkStates/{movieId}', methods: ['GET'])]
    public function checkMovieStates(string $movieId, Request $request, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $movie = $this->movieRepository->findOneById($movieId);

        $favourite = new Favourite();
        $favourite->setIsFavourite(true);
        $favourite->setUserName($user);
        $favourite->setMovie($movie);

        $isFavourite = false;
        $wantToSee = false;

        foreach ($user->getSpecials()->getValues() as $fav) {
            if ($favourite->equals($fav)) {
                $isFavourite = true;
                break;
            }
        }

        $favourite->setIsFavourite(false);

        foreach ($user->getSpecials()->getValues() as $fav) {
            if ($favourite->equals($fav)) {
                $wantToSee = true;
                break;
            }
        }

        $rating = 0;
        $existingRate = $this->rateRepository->findOneBy(['user_name' => $user, 'movie' => $movie]);
        if ($existingRate) {
            $rating = $existingRate->getRate();
        }

        $data = [
            'favourite' => $isFavourite,
            'wantToSee' => $wantToSee,
            'rate' => $rating
        ];

        return new JsonResponse($data);
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/rateMovie/{movieId}', methods: ['POST'])]
    public function rateMovie(Request $request, string $movieId, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $data = json_decode($request->getContent(), true);
        $rating = $data['rate'];

        $movie = $this->movieRepository->findOneById($movieId);

        $existingRate = $this->rateRepository->findOneBy(['user_name' => $user, 'movie' => $movie]);

        if ($existingRate) {
            $oldRating = $existingRate->getRate();
            $existingRate->setRate($rating);
            $this->rateRepository->save($existingRate, true);
            $movie->updateRating($rating, false, $oldRating);
            $this->movieRepository->save($movie, true);
            return new JsonResponse(["message" => "Rating updated"]);
        }

        $rate = new Rate();
        $rate->setMovie($movie);
        $rate->setUserName($user);
        $rate->setRate($rating);

        $user->addRate($rate);
        $this->rateRepository->save($rate, true);
        $movie->updateRating($rating);
        $this->movieRepository->save($movie, true);
        return new JsonResponse(["message" => "Added rate to database"]);
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/checkLike/{id}', methods: ['GET'])]
    public function checkLikes(string $id, AccessTokenHandler $accessTokenHandler, Request $request): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $review = $this->reviewRepository->findOneById($id);
        $owner = false;

        if ($review->getUserName()->getId() == $user->getId()) {
            $owner = true;
        }

        $alreadyLiked = $user->getLikeReview()->getValues();
        foreach ($alreadyLiked as $like) {
            if ($review->getId() == $like->getId()) {
                $response = [
                    "liked" => true,
                    "owner" => $owner
                ];
                return new JsonResponse($response);
            }
        }

        $response = [
            "liked" => false,
            "owner" => $owner
        ];
        return new JsonResponse($response);
    }


    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/likeReview/{id}', methods: ['POST'])]
    public function likeReview(string $id, AccessTokenHandler $accessTokenHandler, Request $request): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $review = $this->reviewRepository->findOneById($id);

        $alreadyLiked = $user->getLikeReview()->getValues();
        foreach ($alreadyLiked as $like) {
            if ($review->getId() == $like->getId()) {
                $this->userRepository->removeReview($user, $like);
                $review->setNumOfLikes($review->getNumOfLikes() - 1);
                $this->reviewRepository->save($review, true);
                return new JsonResponse("Unlike review");
            }
        }
        $this->userRepository->likeReview($user, $review);
        $review->setNumOfLikes($review->getNumOfLikes() + 1);
        $this->reviewRepository->save($review, true);

        return new JsonResponse("Like review");

    }
}