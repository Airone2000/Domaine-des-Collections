<?php

namespace App\Entity;

use App\Repository\FormComponentPositionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormComponentPositionRepository::class)
 */
class FormComponentPosition
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var FormComponent
     * @ORM\OneToOne(targetEntity="App\Entity\FormComponent", inversedBy="position")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private FormComponent $formComponent;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $fallback = 0;

    /**
     * @var null|int
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $smartphone;

    /**
     * @var null|int
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $tablet;

    /**
     * @var null|int
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $desktop;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FormComponent
     */
    public function getFormComponent(): FormComponent
    {
        return $this->formComponent;
    }

    /**
     * @param FormComponent $formComponent
     * @return FormComponentPosition
     */
    public function setFormComponent(FormComponent $formComponent): FormComponentPosition
    {
        $this->formComponent = $formComponent;
        return $this;
    }

    /**
     * @return int
     */
    public function getFallback(): int
    {
        return $this->fallback;
    }

    /**
     * @param int $fallback
     * @return FormComponentPosition
     */
    public function setFallback(int $fallback): FormComponentPosition
    {
        $this->fallback = $fallback;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSmartphone(): ?int
    {
        return $this->smartphone;
    }

    /**
     * @param int|null $smartphone
     * @return FormComponentPosition
     */
    public function setSmartphone(?int $smartphone): FormComponentPosition
    {
        $this->smartphone = $smartphone;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTablet(): ?int
    {
        return $this->tablet;
    }

    /**
     * @param int|null $tablet
     * @return FormComponentPosition
     */
    public function setTablet(?int $tablet): FormComponentPosition
    {
        $this->tablet = $tablet;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDesktop(): ?int
    {
        return $this->desktop;
    }

    /**
     * @param int|null $desktop
     * @return FormComponentPosition
     */
    public function setDesktop(?int $desktop): FormComponentPosition
    {
        $this->desktop = $desktop;
        return $this;
    }

    public function getFor(string $device): int
    {
        return call_user_func([$this, "get{$device}"]) ?? $this->getFallback();
    }

}
