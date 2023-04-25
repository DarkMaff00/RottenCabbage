<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $add_date = null;

    #[ORM\Column(length: 200)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $num_of_likes = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movie $movie_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddDate(): ?\DateTimeInterface
    {
        return $this->add_date;
    }

    public function setAddDate(\DateTimeInterface $add_date): self
    {
        $this->add_date = $add_date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNumOfLikes(): ?int
    {
        return $this->num_of_likes;
    }

    public function setNumOfLikes(int $num_of_likes): self
    {
        $this->num_of_likes = $num_of_likes;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getMovieId(): ?Movie
    {
        return $this->movie_id;
    }

    public function setMovieId(?Movie $movie_id): self
    {
        $this->movie_id = $movie_id;

        return $this;
    }
}
