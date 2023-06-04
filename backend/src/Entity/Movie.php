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
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $rate = null;

    #[ORM\Column]
    private ?bool $is_movie = null;

    #[ORM\Column]
    private ?int $num_of_ratings = null;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Rate::class)]
    private Collection $rates;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Favourite::class)]
    private Collection $specials;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Review::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->rates = new ArrayCollection();
        $this->specials = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
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

    public function setNumOfRatings(int $num_of_ratings): self
    {
        $this->num_of_ratings = $num_of_ratings;

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
            $rate->setMovie($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getMovie() === $this) {
                $rate->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favourite>
     */
    public function getSpecials(): Collection
    {
        return $this->specials;
    }

    public function addSpecial(Favourite $special): self
    {
        if (!$this->specials->contains($special)) {
            $this->specials->add($special);
            $special->setMovie($this);
        }

        return $this;
    }

    public function removeSpecial(Favourite $special): self
    {
        if ($this->specials->removeElement($special)) {
            // set the owning side to null (unless already changed)
            if ($special->getMovie() === $this) {
                $special->setMovie(null);
            }
        }

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
            $review->setMovie($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getMovie() === $this) {
                $review->setMovie(null);
            }
        }

        return $this;
    }

    public function updateRating(float $rate, bool $isNew = true, float $oldRate = 0.0): self
    {
        $totalRating = $this->getRate() * $this->getNumOfRatings();
        if ($isNew) {
            $this->setNumOfRatings($this->getNumOfRatings() + 1);
        }
        $newRating = ($totalRating + $rate - $oldRate) / $this->getNumOfRatings();
        $this->setRate($newRating);
        return $this;
    }
}
