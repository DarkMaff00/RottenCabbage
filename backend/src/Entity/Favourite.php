<?php

namespace App\Entity;

use App\Repository\FavouriteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavouriteRepository::class)]
class Favourite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $is_favourite = null;

    #[ORM\ManyToOne(inversedBy: 'specials')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_name = null;

    #[ORM\ManyToOne(inversedBy: 'specials')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Movie $movie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsFavourite(): ?bool
    {
        return $this->is_favourite;
    }

    public function setIsFavourite(bool $is_favourite): self
    {
        $this->is_favourite = $is_favourite;

        return $this;
    }

    public function getUserName(): ?User
    {
        return $this->user_name;
    }

    public function setUserName(?User $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
