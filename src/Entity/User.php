<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, groups={"User:Register"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(groups={"User:Register"})
     */
    private ?string $username = null;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var null|UserEmailAddress
     * @ORM\OneToOne(targetEntity="App\Entity\UserEmailAddress", mappedBy="user", cascade={"persist", "remove"}, fetch="EAGER")
     * @Assert\Valid(groups={"User:Register"})
     */
    private ?UserEmailAddress $emailAddress = null;


    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private ?string $password = null;

    /**
     * @var UserBirthdate
     * @ORM\OneToOne(targetEntity="App\Entity\UserBirthdate", mappedBy="user", cascade={"persist", "remove"}, fetch="EAGER")
     */
    private UserBirthdate $birthdate;

    public function __construct()
    {
        $this->setBirthdate(new UserBirthdate());
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): ?string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

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

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return UserEmailAddress|null
     */
    public function getEmailAddress(): ?UserEmailAddress
    {
        return $this->emailAddress;
    }

    /**
     * @param UserEmailAddress|null $emailAddress
     * @return User
     */
    public function setEmailAddress(?UserEmailAddress $emailAddress): User
    {
        if ($emailAddress instanceof UserEmailAddress) {
            $emailAddress->setUser($this);
        }

        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @return UserBirthdate
     */
    public function getBirthdate(): UserBirthdate
    {
        return $this->birthdate;
    }

    /**
     * @param UserBirthdate $birthdate
     * @return User
     */
    public function setBirthdate(UserBirthdate $birthdate): User
    {
        $birthdate
            ->setUser($this)
            ->setVisible(false)
        ;
        $this->birthdate = $birthdate;
        return $this;
    }

}
