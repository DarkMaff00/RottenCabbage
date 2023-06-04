<?php

namespace App\Controller;


use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\NonUniqueResultException;
use ErrorException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
            $genre = $desc['genres'][0]['name'] ?? "Movie";


            $data[] = [
                'id' => $id,
                'title' => $title,
                'genre' => $genre,
                'rate' => $movie->getRate(),
                'critic' => $desc["vote_average"],
                'poster' => 'https://image.tmdb.org/t/p/original/' . $desc['poster_path']
            ];
        }

        usort($data, function ($a, $b) {
            $sumA = $a['rate'] + $a['critic'];
            $sumB = $b['rate'] + $b['critic'];
            return $sumB <=> $sumA;
        });

        return new JsonResponse($data);
    }

    /**
     * @throws GuzzleException
     */
    #[Route('/premiers', methods: ['GET'])]
    public function getPremiers(): JsonResponse
    {
        $premiers = [];
        $client = new Client([
            'verify' => false
        ]);
        $token = $_ENV['API_TOKEN'];
        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/upcoming', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true)["results"];

        $today = strtotime(date('Y-m-d'));

        foreach ($data as $movie) {
            $releaseDate = strtotime($movie['release_date']);

            if ($releaseDate > $today) {
                $videosResponse = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $movie['id'] . '/videos', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token
                    ]
                ]);
                $videosBody = $videosResponse->getBody()->getContents();
                $videos = json_decode($videosBody, true);

                $trailerKey = null;
                foreach ($videos['results'] as $video) {
                    if ($video['type'] === 'Trailer') {
                        $trailerKey = $video['key'];
                        break;
                    }
                }

                $premiers[] = [
                    'id' => $movie['id'],
                    'title' => $movie['title'],
                    'poster' => 'https://image.tmdb.org/t/p/original/' . $movie['poster_path'],
                    'release' => $movie['release_date'],
                    'desc' => $movie['overview'],
                    'trailerKey' => 'https://www.youtube.com/watch?v=' . $trailerKey,
                ];
            }
        }

        usort($premiers, function ($a, $b) {
            return strtotime($a['release']) - strtotime($b['release']);
        });

        return new JsonResponse($premiers);
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


    /**
     * @throws NonUniqueResultException
     * @throws GuzzleException
     */
    #[Route('/movieInfo/{uuid}', methods: ['GET'])]
    public function movieInfo(string $uuid): JsonResponse
    {
        $client = new Client([
            'verify' => false
        ]);
        $token = $_ENV['API_TOKEN'];
        $movie = $this->movieRepository->findOneById($uuid);
        if (!$movie) {
            return new JsonResponse(['message' => 'Movie not found'], 404);
        }

        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $uuid, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $body = $response->getBody()->getContents();
        $desc = json_decode($body, true);

        $genre = $desc['genres'][0]['name'] ?? "Movie";

        $movie = $this->movieRepository->findOneById($uuid);

        $videosResponse = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $uuid . '/videos', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $videosBody = $videosResponse->getBody()->getContents();
        $videos = json_decode($videosBody, true);

        $trailerKey = null;
        foreach ($videos['results'] as $video) {
            if ($video['type'] === 'Trailer') {
                $trailerKey = $video['key'];
                break;
            }
        }

        $creditsResponse = $client->request('GET', 'https://api.themoviedb.org/3/movie/' . $uuid . '/credits', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        $creditsBody = $creditsResponse->getBody()->getContents();
        $credits = json_decode($creditsBody, true);

        $directorName = '';

        foreach ($credits['crew'] as $crew) {
            if ($crew['job'] === 'Director') {
                $directorName = $crew['name'];
                break;
            }
        }

        $data = [
            'title' => $desc['title'],
            'genre' => $genre,
            'rate' => $movie->getRate(),
            'critic' => $desc['vote_average'],
            'poster' => 'https://image.tmdb.org/t/p/original' . $desc['poster_path'],
            'desc' => $desc['overview'],
            'production' => $desc['production_countries'][0]['name'] ?? "Unknown",
            'release' => $desc['release_date'],
            'trailerKey' => 'https://www.youtube.com/watch?v=' . $trailerKey,
            'director' => $directorName,
        ];

        return new JsonResponse($data);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/getRate/{movieId}', methods: ['GET'])]
    public function getRate(string $movieId): JsonResponse
    {
        $movie = $this->movieRepository->findOneById($movieId);
        $data = $movie->getRate();

        return new JsonResponse($data);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/getReviews/{movieId}', methods: ['GET'])]
    public function getReviews(string $movieId): JsonResponse
    {
        $movie = $this->movieRepository->findOneById($movieId);
        $data = $movie->getReviews();
        $reviews = [];
        foreach ($data as $rev) {
            $reviews[] = [
                'email' => $rev->getUserName()->getEmail(),
                'id' => $rev->getId(),
                'date' => $rev->getAddDate(),
                'context' => $rev->getDescription(),
                'likes' => $rev->getNumOfLikes()
            ];
        }

        return new JsonResponse($reviews);
    }
}