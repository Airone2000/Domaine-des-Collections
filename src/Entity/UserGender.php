<?php

namespace App\Entity;

use App\Repository\UserGenderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserGenderRepository::class)
 */
class UserGender
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
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="gender")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private User $user;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private ?string $value;

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
     * @return UserGender
     */
    public function setUser(User $user): UserGender
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return UserGender
     */
    public function setValue(?string $value): UserGender
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
     * @return UserGender
     */
    public function setVisible(bool $visible): UserGender
    {
        $this->visible = $visible;
        return $this;
    }
}
