<?php

namespace App\Entity;

use App\Repository\ThingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ThingRepository::class)
 */
class Thing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collection")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Collection $collection;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Value", mappedBy="thing", cascade={"persist", "remove"}, fetch="EAGER")
     */
    private DoctrineCollection $values;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return Thing
     */
    public function setCollection(Collection $collection): Thing
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Thing
     */
    public function setName(?string $name): Thing
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection|DoctrineCollection
     */
    public function getValues()
    {
        return $this->values;
    }


}
