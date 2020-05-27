<?php

namespace App\Entity;

use App\Repository\FormRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormRepository::class)
 */
class Form
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var Collection
     * @ORM\OneToOne(targetEntity="App\Entity\Collection", inversedBy="form")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Collection $collection;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private bool $inEdition = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private bool $valid = false;

    /**
     * @var DoctrineCollection
     * @ORM\OneToMany(targetEntity="App\Entity\FormComponent", mappedBy="form", cascade={"persist", "remove"}, fetch="EAGER")
     */
    private $formComponents;

    public function __construct()
    {
        $this->formComponents = new ArrayCollection();
    }

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
     * @return Form
     */
    public function setCollection(Collection $collection): Form
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInEdition(): bool
    {
        return $this->inEdition;
    }

    /**
     * @param bool $inEdition
     * @return Form
     */
    public function setInEdition(bool $inEdition): Form
    {
        $this->inEdition = $inEdition;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     * @return Form
     */
    public function setValid(bool $valid): Form
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * @return DoctrineCollection
     */
    public function getFormComponents(): DoctrineCollection
    {
        return $this->formComponents;
    }

    /**
     * @param DoctrineCollection $formComponents
     * @return Form
     */
    public function setFormComponents(DoctrineCollection $formComponents): Form
    {
        $this->formComponents = $formComponents;
        return $this;
    }

    /**
     * @param FormComponent $formComponent
     * @return $this
     */
    public function addFormComponent(FormComponent $formComponent): Form
    {
        $formComponent->setForm($this);
        $this->formComponents->add($formComponent);
        return $this;
    }

}
