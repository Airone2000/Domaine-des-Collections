<?php

namespace App\Entity;

use App\Repository\CollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CollectionRepository::class)
 * @ORM\EntityListeners("\App\EntityListener\CollectionListener")
 */
class Collection
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Assert\Length(
     *     max="200",
     *     groups={"Collection:Create"}
     * )
     */
    private ?string $name = null;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private bool $private = true;

    /**
     * @var DoctrineCollection
     * @ORM\OneToMany(targetEntity="App\Entity\CollectionFounderMember", mappedBy="collection")
     */
    private DoctrineCollection $founders;

    /**
     * @var DoctrineCollection
     * @ORM\OneToMany(targetEntity="App\Entity\CollectionAdminMember", mappedBy="collection")
     */
    private DoctrineCollection $admins;

    /**
     * @var Form
     * @ORM\OneToOne(targetEntity="App\Entity\Form", mappedBy="collection", cascade={"persist", "remove"})
     */
    private Form $form;

    public function __construct()
    {
        $this->founders = new ArrayCollection();
        $this->admins = new ArrayCollection();
        // Build the initial form
        $this->buildForm();
    }

    /**
     * Build here instead of in prePersist.
     * This allows use to manipulate the form before being saved
     * @internal
     */
    private function buildForm(): void
    {
        $form = new Form();
        $form->setValid(true);
        $this->setForm($form);

        // >> First component (label)
        $formComponent = new FormComponent();
        $formComponent->getPosition()->setFallback(1);
        $formComponent->getSize()->setFallback(50);
        $form->addFormComponent($formComponent);
        $formComponent->getWidget()
            ->setType(FormComponentWidget::LABEL)
            ->addOption('label', 'Champ 1')
        ;

        // >> Second component (text input)
        $formComponent = new FormComponent();
        $formComponent->getPosition()->setFallback(2);
        $formComponent->getSize()->setFallback(50);
        $form->addFormComponent($formComponent);
        $formComponent->getWidget()->setType(FormComponentWidget::TEXT);

        // >> Third component (label)
        $formComponent = new FormComponent();
        $formComponent->getPosition()->setFallback(3);
        $formComponent->getSize()->setFallback(50);
        $form->addFormComponent($formComponent);
        $formComponent->getWidget()
            ->setType(FormComponentWidget::LABEL)
            ->addOption('label', 'Champ 2')
        ;

        // >> Fourth component (text input)
        $formComponent = new FormComponent();
        $formComponent->getPosition()->setFallback(4);
        $formComponent->getSize()->setFallback(50);
        $form->addFormComponent($formComponent);
        $formComponent->getWidget()->setType(FormComponentWidget::TEXT);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return Collection
     */
    public function setName(?string $name): Collection
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->private;
    }

    /**
     * @param bool $private
     * @return Collection
     */
    public function setPrivate(bool $private): Collection
    {
        $this->private = $private;
        return $this;
    }

    /**
     * @return DoctrineCollection
     */
    public function getFounders(): DoctrineCollection
    {
        return $this->founders;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdmins(): ArrayCollection
    {
        return $this->admins;
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
     * @return Collection
     */
    public function setForm(Form $form): Collection
    {
        $form->setCollection($this);
        $this->form = $form;
        return $this;
    }

}
