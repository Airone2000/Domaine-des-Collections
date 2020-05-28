<?php

namespace App\Entity;

use App\Repository\ValueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ValueRepository::class)
 */
class Value
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var Thing
     * @ORM\ManyToOne(targetEntity="App\Entity\Thing", inversedBy="values")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Thing $thing;

    /**
     * @var FormComponent
     * @ORM\ManyToOne(targetEntity="App\Entity\FormComponent", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private FormComponent $formComponent;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private ?string $lineOfTextValue;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Thing
     */
    public function getThing(): Thing
    {
        return $this->thing;
    }

    /**
     * @param Thing $thing
     * @return Value
     */
    public function setThing(Thing $thing): Value
    {
        $this->thing = $thing;
        return $this;
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
     * @return Value
     */
    public function setFormComponent(FormComponent $formComponent): Value
    {
        $this->formComponent = $formComponent;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLineOfTextValue(): ?string
    {
        return $this->lineOfTextValue;
    }

    /**
     * @param string|null $lineOfTextValue
     * @return Value
     */
    public function setLineOfTextValue(?string $lineOfTextValue): Value
    {
        $this->lineOfTextValue = $lineOfTextValue;
        return $this;
    }

    /**
     * @internal
     */
    private function requiresFormComponent()
    {
        if ($this->formComponent === null) {
            throw new \LogicException('Cannot set value on undefined FormComponent');
        }
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        $this->requiresFormComponent();
        switch ($this->formComponent->getWidget()->getType()) {
            case FormComponentWidget::LINE_OF_TEXT:
                return (string) $this->getLineOfTextValue();
        }
        return null;
    }

    public function setValue($value) : Value
    {
        $this->requiresFormComponent();

        switch ($this->formComponent->getWidget()->getType()) {
            case FormComponentWidget::LINE_OF_TEXT:
                if ($value === null || is_string($value)) {
                    $this->setLineOfTextValue($value);
                }
                break;
        }


        return $this;
    }

    /**
     * @param array $violations
     * @return bool
     */
    public function isValid(?array &$violations = []): bool
    {
        $this->requiresFormComponent();
        $widget = $this->formComponent->getWidget();
        $widgetOptions = $widget->getOptions() ?? [];
        switch ($this->formComponent->getWidget()->getType()) {
            case FormComponentWidget::LINE_OF_TEXT:
                $value = $this->getLineOfTextValue();
                if (isset($widgetOptions[$widget::REQUIRED_CONSTRAINT])) {
                    $isRequired = $widget::REQUIRED_CONSTRAINT;
                    if ($isRequired && (null === $value || mb_strlen(trim((string) $value)) === 0)) {
                        $violations[] = [
                            'constraint' => $widget::REQUIRED_CONSTRAINT,
                            'message' => 'This value is required'
                        ];
                    }
                }
                break;
        }
        return empty($violations);
    }

}
