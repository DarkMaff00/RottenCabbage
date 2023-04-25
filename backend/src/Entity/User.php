<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\CustomIdGenerator(class: "Ramsey\Uuid\Doctrine\UuidGenerator")]
    private ?UuidInterface $id = null;

    #[ORM\Column(length: 70)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $is_admin = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserInfo $user_details = null;

    #[ORM\ManyToMany(targetEntity: self::class)]
    #[ORM\JoinTable(name:"following")]
    private Collection $following;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'user_id', targetEntity: Rate::class, orphanRemoval: true)]
    private Collection $rates;

    #[ORM\ManyToMany(targetEntity: Movie::class)]
    #[ORM\JoinTable(name:"favourite")]
    private Collection $favourite;

    #[ORM\ManyToMany(targetEntity: Movie::class)]
    #[ORM\JoinTable(name:"want_to_see")]
    private Collection $want_to_see;

    #[ORM\ManyToMany(targetEntity: Review::class)]
    #[ORM\JoinTable(name:"like_review")]
    private Collection $like_review;

    public function __construct()
    {
        $this->following = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->favourite = new ArrayCollection();
        $this->want_to_see = new ArrayCollection();
        $this->like_review = new ArrayCollection();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isIsAdmin(): ?bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): self
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    public function getUserDetails(): ?UserInfo
    {
        return $this->user_details;
    }

    public function setUserDetails(UserInfo $user_details): self
    {
        $this->user_details = $user_details;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    public function addFollowing(self $following): self
    {
        if (!$this->following->contains($following)) {
            $this->following->add($following);
        }

        return $this;
    }

    public function removeFollowing(self $following): self
    {
        $this->following->removeElement($following);

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
            $review->setUserId($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUserId() === $this) {
                $review->setUserId(null);
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
            $rate->setUserId($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getUserId() === $this) {
                $rate->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getFavourite(): Collection
    {
        return $this->favourite;
    }

    public function addFavourite(Movie $favourite): self
    {
        if (!$this->favourite->contains($favourite)) {
            $this->favourite->add($favourite);
        }

        return $this;
    }

    public function removeFavourite(Movie $favourite): self
    {
        $this->favourite->removeElement($favourite);

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getWantToSee(): Collection
    {
        return $this->want_to_see;
    }

    public function addWantToSee(Movie $wantToSee): self
    {
        if (!$this->want_to_see->contains($wantToSee)) {
            $this->want_to_see->add($wantToSee);
        }

        return $this;
    }

    public function removeWantToSee(Movie $wantToSee): self
    {
        $this->want_to_see->removeElement($wantToSee);

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getLikeReview(): Collection
    {
        return $this->like_review;
    }

    public function addLikeReview(Review $likeReview): self
    {
        if (!$this->like_review->contains($likeReview)) {
            $this->like_review->add($likeReview);
        }

        return $this;
    }

    public function removeLikeReview(Review $likeReview): self
    {
        $this->like_review->removeElement($likeReview);

        return $this;
    }
}
