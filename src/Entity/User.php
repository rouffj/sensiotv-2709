<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['user_list', 'user_detail'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    #[Groups(['user_list', 'user_detail'])]
    private $firstName;

    /**
     * @ORM\Column(type="string", length=60)
     */
    #[Groups(['user_list', 'user_detail'])]
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\Email()]
    #[Groups(['user_list', 'user_detail'])]
    private $email;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    #[Assert\Regex('/^\d{10,10}$/')]
    #[Groups(['user_detail'])]
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\NotCompromisedPassword()]
//    #[Ignore]
    private $password;

    #[Groups(['user_detail'])]
    private $reviews = [];

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToOne(targetEntity=ApiUser::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $apiUser;

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

//    #[Assert\IsTrue(message: 'Your phone is invalid')]
//    public function isPhoneValid(): bool
//    {
//        return 1 === preg_match('/^\d{10,10}$/', $this->phone);
//    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getReviews()
    {
        return $this->reviews;
    }

    public function addReview(Review $review)
    {
        $this->reviews[] = $review;
    }

    public function getApiUser(): ?ApiUser
    {
        return $this->apiUser;
    }

    public function setApiUser(ApiUser $apiUser): self
    {
        // set the owning side of the relation if necessary
        if ($apiUser->getUser() !== $this) {
            $apiUser->setUser($this);
        }

        $this->apiUser = $apiUser;

        return $this;
    }
}
