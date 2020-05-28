<?php

namespace App\Entity;

use App\Enum\PublicationStatus;
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
     * @var bool
     * @ORM\Column(type="boolean", options={"default"=0})
     */
    private bool $valid = false;

    /**
     * @var array|null
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $violations;

    /**
     * @var string
     * @ORM\Column(type="string", length=15, options={"default"="draft"})
     */
    private string $publicationStatus = PublicationStatus::DRAFT;

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

    /**
     * @param bool $returnCachedValue
     * @param array|null $violations
     * @return bool
     */
    public function isValid(bool $returnCachedValue = true, ?array &$violations = []): bool
    {
        if (false === $returnCachedValue) {
            $invalidValueCount = 0;
            /* @var Value $value */
            foreach ($this->values as $value) {
                if (false === $value->isValid($nestedViolations)) {
                    ++$invalidValueCount;
                    $violations[(string) $value->getFormComponent()->getId()] = $nestedViolations;
                }
            }
            $this->valid = $invalidValueCount === 0;
            $this->violations = $violations;
        }
        $violations = $this->getViolations();
        return $this->valid;
    }

    /**
     * @param bool $valid
     * @return Thing
     */
    public function setValid(bool $valid): Thing
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getViolations(): ?array
    {
        return $this->violations;
    }

    /**
     * @param array|null $violations
     * @return Thing
     */
    public function setViolations(?array $violations): Thing
    {
        $this->violations = $violations;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicationStatus(): string
    {
        return $this->publicationStatus;
    }

    /**
     * @param string $publicationStatus
     * @return Thing
     */
    public function setPublicationStatus(string $publicationStatus): Thing
    {
        $this->publicationStatus = $publicationStatus;
        return $this;
    }

}
