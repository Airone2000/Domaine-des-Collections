<?php

namespace App\Entity;

use App\Repository\FormComponentSizeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormComponentSizeRepository::class)
 */
class FormComponentSize
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
     * @ORM\OneToOne(targetEntity="App\Entity\FormComponent", inversedBy="size")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private FormComponent $formComponent;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", scale=2, precision=5, nullable=false)
     */
    private ?float $fallback = 100;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", scale=2, precision=5, nullable=true)
     */
    private ?float $smartphone = null;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", scale=2, precision=5, nullable=true)
     */
    private ?float $tablet = null;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", scale=2, precision=5, nullable=true)
     */
    private ?float $desktop = null;

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
     * @return FormComponentSize
     */
    public function setFormComponent(FormComponent $formComponent): FormComponentSize
    {
        $this->formComponent = $formComponent;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFallback(): ?float
    {
        return $this->fallback;
    }

    /**
     * @param float|null $fallback
     * @return FormComponentSize
     */
    public function setFallback(?float $fallback): FormComponentSize
    {
        $this->fallback = $fallback;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getSmartphone(): ?float
    {
        return $this->smartphone;
    }

    /**
     * @param float|null $smartphone
     * @return FormComponentSize
     */
    public function setSmartphone(?float $smartphone): FormComponentSize
    {
        $this->smartphone = $smartphone;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTablet(): ?float
    {
        return $this->tablet;
    }

    /**
     * @param float|null $tablet
     * @return FormComponentSize
     */
    public function setTablet(?float $tablet): FormComponentSize
    {
        $this->tablet = $tablet;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getDesktop(): ?float
    {
        return $this->desktop;
    }

    /**
     * @param float|null $desktop
     * @return FormComponentSize
     */
    public function setDesktop(?float $desktop): FormComponentSize
    {
        $this->desktop = $desktop;
        return $this;
    }

    public function getFor(string $device): float
    {
        return call_user_func([$this, "get{$device}"]) ?? $this->getFallback();
    }

}
