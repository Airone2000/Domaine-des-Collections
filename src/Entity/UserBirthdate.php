<?php

namespace App\Entity;

use App\Repository\UserBirthdateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserBirthdateRepository::class)
 */
class UserBirthdate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="birthdate")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private User $user;

    /**
     * @var null|\DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private ?\DateTime $value;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private bool $visible;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return UserBirthdate
     */
    public function setUser(User $user): UserBirthdate
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getValue(): ?\DateTime
    {
        return $this->value;
    }

    /**
     * @param \DateTime|null $value
     * @return UserBirthdate
     */
    public function setValue(?\DateTime $value): UserBirthdate
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return UserBirthdate
     */
    public function setVisible(bool $visible): UserBirthdate
    {
        $this->visible = $visible;
        return $this;
    }


}
