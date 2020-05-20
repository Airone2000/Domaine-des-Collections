<?php

namespace App\Entity;

use App\Repository\UserEmailAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserEmailAddressRepository::class)
 * @UniqueEntity(fields={"email"}, groups={"User:Register"})
 */
class UserEmailAddress
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @var null|User
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="emailAddress")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private ?User $user = null;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=false, unique=true)
     * @Assert\NotBlank(groups={"User:Register"})
     */
    private ?string $email = null;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private bool $verified = false;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $verifiedAt = null;

    /**
     * @var string
     * @ORM\Column(type="uuid")
     */
    private ?string $verificationToken;

    public function __construct()
    {
        $this->verificationToken = Uuid::uuid4()->toString();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return UserEmailAddress
     */
    public function setUser(?User $user): UserEmailAddress
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return UserEmailAddress
     */
    public function setEmail(?string $email): UserEmailAddress
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified;
    }

    /**
     * @param bool $verified
     * @return UserEmailAddress
     */
    public function setVerified(bool $verified): UserEmailAddress
    {
        $this->verified = $verified;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getVerifiedAt(): ?\DateTime
    {
        return $this->verifiedAt;
    }

    /**
     * @param \DateTime|null $verifiedAt
     * @return UserEmailAddress
     */
    public function setVerifiedAt(?\DateTime $verifiedAt): UserEmailAddress
    {
        $this->verifiedAt = $verifiedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getVerificationToken(): string
    {
        return $this->verificationToken;
    }

    /**
     * @param string $verificationToken
     * @return UserEmailAddress
     */
    public function setVerificationToken(string $verificationToken): UserEmailAddress
    {
        $this->verificationToken = $verificationToken;
        return $this;
    }

}
