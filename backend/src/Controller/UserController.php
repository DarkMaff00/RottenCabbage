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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
    private MovieRepository $movieRepository;
    private RateRepository $rateRepository;
    private FavouriteRepository $favouriteRepository;
    private UserPasswordHasherInterface $passwordHashed;
    private ReviewRepository $reviewRepository;
    private AccessTokenHandler $accessTokenHandler;

    public function __construct(UserRepository              $userRepository,
                                UserPasswordHasherInterface $passwordHashed,
                                MovieRepository             $movieRepository,
                                FavouriteRepository         $favouriteRepository,
                                RateRepository              $rateRepository,
                                ReviewRepository            $reviewRepository,
                                AccessTokenHandler          $accessTokenHandler)
    {
        $this->userRepository = $userRepository;
        $this->passwordHashed = $passwordHashed;
        $this->movieRepository = $movieRepository;
        $this->favouriteRepository = $favouriteRepository;
        $this->rateRepository = $rateRepository;
        $this->reviewRepository = $reviewRepository;
        $this->accessTokenHandler = $accessTokenHandler;
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
            return new JsonResponse(['message' => 'User already exists. Change your email.'], 409);
        }

        $user = new User();
        $hashedPassword = $this->passwordHashed->hashPassword($user, $password);

        $user
            ->setEmail($email)
            ->setPassword($hashedPassword)
            ->setFirstName($firstName)
            ->setLastName($lastName);

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

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/following', methods: ['GET'])]
    public function getFollowingUsers(Request $request): JsonResponse
    {
        try {
            $user = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e->getMessage()], 400);
        }

        $data = [];
        $following = $user->getFollowing();
        foreach ($following as $follow) {
            $user = $this->userRepository->findOneById($follow);
            $data[] = [
                'id' => $follow,
                'email' => $user->getEmail(),
                'name' => $user->getFirstName(),
                'surname' => $user->getLastName()
            ];
        }

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
    public function getUserInfo(string $uuid, Request $request): JsonResponse
    {
        $follows = false;
        try {
            $mainUser = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e->getMessage()], 400);
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
    public function followUser(string $uuid, Request $request): JsonResponse
    {
        try {
            $user = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e->getMessage()], 400);
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
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/deleteAccount', methods: ['DELETE'])]
    public function deleteUser(Request $request): JsonResponse
    {
        try {
            $user = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e->getMessage()], 400);
        }

        $data = json_decode($request->getContent(), true);
        $password = $data['password'];

        if (!$this->passwordHashed->isPasswordValid($user, $password)) {
            return new JsonResponse(["message" => "Invalid password"], 400);
        }

        $this->userRepository->remove($user, true);

        return new JsonResponse(["message" => "User deleted successfully"]);
    }


    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/changePassword', methods: ['PUT'])]
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $user = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e->getMessage()], 400);
        }

        $data = json_decode($request->getContent(), true);
        $password = $data['password'];
        $newPassword = $data['new_password'];

        if (!$this->passwordHashed->isPasswordValid($user, $password)) {
            return new JsonResponse(["message" => "Invalid password"], 400);
        }

        $hashedPassword = $this->passwordHashed->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user, $hashedPassword);

        return new JsonResponse(["message" => "Password changed successfully"]);
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/addFavourite/{movieId}', methods: ['POST'])]
    public function favouriteMovie(string $movieId, Request $request): JsonResponse
    {
        try {
            $user = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e->getMessage()], 400);
        }

        $data = json_decode($request->getContent(), true);
        $isFavourite = $data['isFavourite'];

        $movie = $this->movieRepository->findOneById($movieId);

        $favourite = new Favourite();
        $favourite->setIsFavourite($isFavourite)
            ->setUserName($user)
            ->setMovie($movie);

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

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/checkStates/{movieId}', methods: ['GET'])]
    public function checkMovieStates(string $movieId, Request $request): JsonResponse
    {
        try {
            $user = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }

        $movie = $this->movieRepository->findOneById($movieId);

        $isFavourite = $this->favouriteRepository->findOneBy(['user_name' => $user, 'movie' => $movie, 'is_favourite' => true]);

        $wantToSee = $this->favouriteRepository->findOneBy(['user_name' => $user, 'movie' => $movie, 'is_favourite' => false]);

        $existingRate = $this->rateRepository->findOneBy(['user_name' => $user, 'movie' => $movie]);
        $rating = $existingRate ? $existingRate->getRate() : 0;

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
    public function rateMovie(Request $request, string $movieId): JsonResponse
    {
        try {
            $user = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e->getMessage()], 400);
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
        $rate->setMovie($movie)
            ->setUserName($user)
            ->setRate($rating);

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
    public function checkLikes(string $id, Request $request): JsonResponse
    {
        try {
            $user = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e->getMessage()], 400);
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
    public function likeReview(string $id, Request $request): JsonResponse
    {
        try {
            $user = $this->accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e->getMessage()], 400);
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

    /**
     * @throws GuzzleException
     * @throws NonUniqueResultException
     */
    #[Route('/getSpecials/{uuid}', methods: ['GET'])]
    public function returnSpecials(string $uuid): JsonResponse
    {
        $user = $this->userRepository->findOneById($uuid);
        $rates = $user->getRates();

        $ratings = array_map(function ($rate) {
            $movie = $rate->getMovie()->getId();
            return [
                "data" => $this->getMovieInfo($movie),
                "rate" => $rate->getRate()
            ];
        }, $rates->getValues());

        $specials = $user->getSpecials();
        $favourites = [];
        $wantToSee = [];
        foreach ($specials as $spec) {
            $movie = $spec->getMovie()->getId();
            $movieInfo = $this->getMovieInfo($movie);
            if ($spec->isIsFavourite()) {
                $favourites[] = ["data" => $movieInfo];
            } else {
                $wantToSee[] = ["data" => $movieInfo];
            }
        }

        usort($ratings, function ($a, $b) {
            return $b["rate"] - $a["rate"];
        });

        $data = [$ratings, $favourites, $wantToSee];

        return new JsonResponse($data);
    }

    /**
     * @throws GuzzleException
     */
    public function getMovieInfo(int $id): array
    {
        $client = new Client([
            'verify' => false
        ]);
        $token = $_ENV['API_TOKEN'];
        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $id,
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);
        $body = $response->getBody()->getContents();
        $desc = json_decode($body, true);

        return [
            'id' => $id,
            'title' => $desc['title'],
            'poster' => 'https://image.tmdb.org/t/p/original' . $desc['poster_path']
        ];
    }

}