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
 * @ORM\EntityListeners("App\EntityListener\UserEmailAddressListener")
 * @UniqueEntity(fields={"email"}, groups={"User:Register", "UserEmailAddress:Update"}, ignoreNull=true)
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
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Email(groups={"User:Register"})
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

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private bool $newsLetterAccepted = false;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private array $historyOfVerifiedEmailAddresses = [];

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default"=1})
     */
    private bool $historyOfVerifiedEmailAddressesKept = true;

    public function __construct()
    {
        $this->renewVerificationToken();
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

    /**
     * @return $this
     */
    public function renewVerificationToken(): UserEmailAddress
    {
        $this->verificationToken = Uuid::uuid4()->toString();
        return $this;
    }

    /**
     * @return bool
     */
    public function isNewsLetterAccepted(): bool
    {
        return $this->newsLetterAccepted;
    }

    /**
     * @param bool $newsLetterAccepted
     * @return UserEmailAddress
     */
    public function setNewsLetterAccepted(bool $newsLetterAccepted): UserEmailAddress
    {
        $this->newsLetterAccepted = $newsLetterAccepted;
        return $this;
    }

    /**
     * @return array
     */
    public function getHistoryOfVerifiedEmailAddresses(): array
    {
        return $this->historyOfVerifiedEmailAddresses;
    }

    /**
     * @param array $historyOfVerifiedEmailAddresses
     * @return UserEmailAddress
     */
    public function setHistoryOfVerifiedEmailAddresses(array $historyOfVerifiedEmailAddresses): UserEmailAddress
    {
        $this->historyOfVerifiedEmailAddresses = $historyOfVerifiedEmailAddresses;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHistoryOfVerifiedEmailAddressesKept(): bool
    {
        return $this->historyOfVerifiedEmailAddressesKept;
    }

    /**
     * @param bool $historyOfVerifiedEmailAddressesKept
     * @return UserEmailAddress
     */
    public function setHistoryOfVerifiedEmailAddressesKept(bool $historyOfVerifiedEmailAddressesKept): UserEmailAddress
    {
        $this->historyOfVerifiedEmailAddressesKept = $historyOfVerifiedEmailAddressesKept;
        return $this;
    }


}
