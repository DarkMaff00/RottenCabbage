<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use App\Security\AccessTokenHandler;
use Doctrine\ORM\NonUniqueResultException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class ReviewController extends AbstractController
{
    private MovieRepository $movieRepository;
    private ReviewRepository $reviewRepository;

    public function __construct(MovieRepository $movieRepository, ReviewRepository $reviewRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * @throws JWTDecodeFailureException
     * @throws NonUniqueResultException
     */
    #[Route('/addReview/{movieId}', methods: ['POST'])]
    public function addReview(Request $request, string $movieId, AccessTokenHandler $accessTokenHandler): JsonResponse
    {
        try {
            $user = $accessTokenHandler->getUserBadgeFrom($request);
        } catch (BadCredentialsException $e) {
            return new JsonResponse(["message" => $e], 400);
        }
        $movie = $this->movieRepository->findOneById($movieId);
        $requestData = json_decode($request->getContent(), true);

        $context = $requestData['desc'];

        $existingReview = $this->reviewRepository->findOneBy(['user_name' => $user, 'movie' => $movie]);
        if ($existingReview) {
            return new JsonResponse("You can add only one review for one movie.");
        }

        $review = new Review();
        $review->setUserName($user);
        $review->setMovie($movie);
        $review->setDescription($context);
        $review->setAddDate(new \DateTime());

        $this->reviewRepository->save($review, true);

        return new JsonResponse("Review created");
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/deleteReview/{id}', methods: ['DELETE'])]
    public function deleteReview(string $id): JsonResponse
    {
        $review = $this->reviewRepository->findOneById($id);
        $this->reviewRepository->remove($review, true);

        return new JsonResponse("Review deleted");
    }
}