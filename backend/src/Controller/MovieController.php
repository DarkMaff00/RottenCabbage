<?php

namespace App\Controller;


use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\DBAL\Driver\OCI8\Exception\Error;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use ErrorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Lcobucci\JWT\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{

    private MovieRepository $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @throws GuzzleException
     */
    #[Route('/ranking', methods: ['GET'])]
    public function getMovies(): JsonResponse
    {
        $client = new Client([
            'verify' => false
        ]);
        $token = $_ENV['API_TOKEN'];
        $movies = $this->movieRepository->findAll();
        $data = [];
        foreach ($movies as $movie) {
            $id = $movie->getId();
            $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $id,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token
                    ]
                ]);
            $body = $response->getBody()->getContents();
            $desc = json_decode($body, true);


            $title = $desc["title"];
            try {
                $genre = $desc["genres"][0]["name"];
            } catch (ErrorException) {
                $genre = "Movie";
            }


            $data[] = [
                'id' => $id,
                'title' => $title,
                'genre' => $genre,
                'rate' => $movie->getRate(),
                'critic' => $desc["vote_average"],
                'poster' => 'https://image.tmdb.org/t/p/original/' . $desc['poster_path']
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/premiers', methods: ['GET'])]
    public function getPremiers(): JsonResponse
    {

        $data = [
            'route' => 'premiers'
        ];
        return new JsonResponse($data);
    }

    /**
     * @throws GuzzleException
     */
    #[Route('/addMovie', methods: ['POST'])]
    public function addMovie(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        $client = new Client([
            'verify' => false
        ]);

        $title = $requestData['title'];

        $token = $_ENV['API_TOKEN'];

        $response = $client->request('GET', 'https://api.themoviedb.org/3/search/movie',
            [
                'query' => [
                    'query' => $title
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

        $body = $response->getBody()->getContents();
        try {
            $data = json_decode($body, true)["results"][0];
        } catch (ErrorException $e) {
            return new JsonResponse(['message' => "No movie in database"]);
        }

        $id = $data["id"];

        $movie = new Movie();

        $movie->setId($id);
        $movie->setIsMovie(true);
        $movie->setRate(0);
        $movie->setNumOfRatings(0);

        try {
            $this->movieRepository->save($movie, true);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['message' => "Movie already in database"]);
        }


        return new JsonResponse(['message' => "Movie added to database"]);
    }

    #[Route('/movieInfo/{uuid}', methods: ['GET'])]
    public function movieInfo(string $uuid): JsonResponse
    {

        $data = [
            'route' => 'movieInfo' . $uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/movieInfo/{uuid}', methods: ['POST'])]
    public function rateMovie(Request $request, string $uuid): JsonResponse
    {

        $data = [
            'route' => 'rateMovie' . $uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/movieInfo/{uuid}', methods: ['POST'])]
    public function favouriteMovie(string $uuid): JsonResponse
    {

        $data = [
            'route' => 'favouriteMovie' . $uuid
        ];
        return new JsonResponse($data);
    }

    #[Route('/movieInfo/{uuid}', methods: ['POST'])]
    public function wantSeeMovie(string $uuid): JsonResponse
    {

        $data = [
            'route' => 'wantSeeMovie' . $uuid
        ];
        return new JsonResponse($data);
    }
}