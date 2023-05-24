<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $first_name = null;

    #[ORM\Column(length: 70)]
    private ?string $last_name = null;

    #[ORM\Column]
    private ?bool $is_admin = false;

    #[ORM\ManyToMany(targetEntity: self::class)]
    private Collection $following;

    #[ORM\ManyToMany(targetEntity: Review::class)]
    private Collection $like_review;

    #[ORM\OneToMany(mappedBy: 'user_name', targetEntity: Rate::class)]
    private Collection $rates;

    #[ORM\OneToMany(mappedBy: 'user_name', targetEntity: Favourite::class)]
    private Collection $specials;

    #[ORM\OneToMany(mappedBy: 'user_name', targetEntity: Review::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->following = new ArrayCollection();
        $this->like_review = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->specials = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?string
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

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
            $rate->setUserName($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getUserName() === $this) {
                $rate->setUserName(null);
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
            $special->setUserName($this);
        }

        return $this;
    }

    public function removeSpecial(Favourite $special): self
    {
        if ($this->specials->removeElement($special)) {
            // set the owning side to null (unless already changed)
            if ($special->getUserName() === $this) {
                $special->setUserName(null);
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
            $review->setUserName($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUserName() === $this) {
                $review->setUserName(null);
            }
        }

        return $this;
    }
}
