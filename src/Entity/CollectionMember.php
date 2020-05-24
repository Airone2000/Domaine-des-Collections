<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="role", type="string", length=10)
 * @ORM\DiscriminatorMap({
 *     "member"="App\Entity\CollectionMember",
 *     "founder"="App\Entity\CollectionFounderMember",
 *     "admin"="App\Entity\CollectionAdminMember"})
 */
class CollectionMember
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private User $user;

    /**
     * @var Collection
     * @ORM\ManyToOne(targetEntity="App\Entity\Collection")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Collection $collection;

    /**
     * @return mixed
     */
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
     * @return CollectionMember
     */
    public function setUser(User $user): CollectionMember
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * @param Collection $collection
     * @return CollectionMember
     */
    public function setCollection(Collection $collection): CollectionMember
    {
        $this->collection = $collection;
        return $this;
    }
}