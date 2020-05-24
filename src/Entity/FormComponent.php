<?php

namespace App\Entity;

use App\Repository\FormComponentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormComponentRepository::class)
 */
class FormComponent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var Form
     * @ORM\ManyToOne(targetEntity="App\Entity\Form", inversedBy="formComponents")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Form $form;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private bool $valid = true;

    /**
     * @var FormComponentSize
     * @ORM\OneToOne(targetEntity="App\Entity\FormComponentSize", mappedBy="formComponent", cascade={"persist", "remove"})
     */
    private FormComponentSize $size;

    /**
     * @var FormComponentPosition
     * @ORM\OneToOne(targetEntity="App\Entity\FormComponentPosition", mappedBy="formComponent", cascade={"persist", "remove"})
     */
    private FormComponentPosition $position;

    /**
     * @var FormComponentWidget
     * @ORM\OneToOne(targetEntity="App\Entity\FormComponentWidget", mappedBy="formComponent", cascade={"persist", "remove"})
     */
    private FormComponentWidget $widget;

    public function __construct()
    {
        // Default size
        $this->setSize(new FormComponentSize());
        // Default widget
        $this->setWidget(new FormComponentWidget());
        // Default position
        $this->setPosition(new FormComponentPosition());
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     * @return FormComponent
     */
    public function setForm(Form $form): FormComponent
    {
        $this->form = $form;
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
     * @return FormComponent
     */
    public function setValid(bool $valid): FormComponent
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * @return FormComponentSize
     */
    public function getSize(): FormComponentSize
    {
        return $this->size;
    }

    /**
     * @param FormComponentSize $size
     * @return FormComponent
     */
    public function setSize(FormComponentSize $size): FormComponent
    {
        $size->setFormComponent($this);
        $this->size = $size;
        return $this;
    }

    /**
     * @return FormComponentPosition
     */
    public function getPosition(): FormComponentPosition
    {
        return $this->position;
    }

    /**
     * @param FormComponentPosition $position
     * @return FormComponent
     */
    public function setPosition(FormComponentPosition $position): FormComponent
    {
        $position->setFormComponent($this);
        $this->position = $position;
        return $this;
    }

    /**
     * @return FormComponentWidget
     */
    public function getWidget(): FormComponentWidget
    {
        return $this->widget;
    }

    /**
     * @param FormComponentWidget $widget
     * @return FormComponent
     */
    public function setWidget(FormComponentWidget $widget): FormComponent
    {
        $widget->setFormComponent($this);
        $this->widget = $widget;
        return $this;
    }

}
