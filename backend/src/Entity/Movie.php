<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $rate = null;

    #[ORM\Column]
    private ?bool $is_movie = null;

    #[ORM\Column(nullable: true)]
    private ?int $num_of_ratings = null;

    #[ORM\OneToMany(mappedBy: 'movie_id', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'movie_id', targetEntity: Rate::class, orphanRemoval: true)]
    private Collection $rates;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(?float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function isIsMovie(): ?bool
    {
        return $this->is_movie;
    }

    public function setIsMovie(bool $is_movie): self
    {
        $this->is_movie = $is_movie;

        return $this;
    }

    public function getNumOfRatings(): ?int
    {
        return $this->num_of_ratings;
    }

    public function setNumOfRatings(?int $num_of_ratings): self
    {
        $this->num_of_ratings = $num_of_ratings;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setMovieId($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getMovieId() === $this) {
                $review->setMovieId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates->add($rate);
            $rate->setMovieId($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getMovieId() === $this) {
                $rate->setMovieId(null);
            }
        }

        return $this;
    }
}
