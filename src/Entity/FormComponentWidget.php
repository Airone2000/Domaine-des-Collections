<?php

namespace App\Entity;

use App\Repository\FormComponentWidgetRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormComponentWidgetRepository::class)
 */
class FormComponentWidget
{
    const NONE = 'none';
    const LABEL = 'label';
    const TEXT = 'text';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var FormComponent
     * @ORM\OneToOne(targetEntity="App\Entity\FormComponent", inversedBy="widget")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private FormComponent $formComponent;

    /**
     * @var string
     * @ORM\Column(type="string", length=15, nullable=false, options={"default"="none"})
     */
    private string $type = self::NONE;

    /**
     * @var array
     * @ORM\Column(type="json", nullable=false)
     */
    private ?array $options = [];

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
     * @return FormComponentWidget
     */
    public function setFormComponent(FormComponent $formComponent): FormComponentWidget
    {
        $this->formComponent = $formComponent;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return FormComponentWidget
     */
    public function setType(string $type): FormComponentWidget
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addOption($key, $value): FormComponentWidget
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function removeOption($key): FormComponentWidget
    {
        unset($this->options[$key]);
        return $this;
    }


}
