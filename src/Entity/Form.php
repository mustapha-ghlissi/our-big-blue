<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FormRepository;
use App\Traits\EntityIdTrait;
use App\Traits\TimeStampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    inputFormats: ['multipart' => ['multipart/form-data']],
    outputFormats: ['jsonld' => ['application/ld+json']],
    normalizationContext: ['groups' => ['form:read']],
    denormalizationContext: ['groups' => ['form:write']]
)]
#[ORM\Entity(repositoryClass: FormRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Form
{
    use EntityIdTrait;
    use TimeStampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['form:read', 'category:read'])]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Category::class, inversedBy: 'form')]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['form:read'])]
    private ?Category $category = null;

    /**
     * @var Collection<int, Field>
     */
    #[ORM\OneToMany(targetEntity: Field::class, mappedBy: 'form', cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Groups(['form:read', 'category:read'])]
    private Collection $fields;

    /**
     * @var Collection<int, CapturedData>
     */
    #[ORM\OneToMany(
        targetEntity: CapturedData::class,
        mappedBy: 'form',
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private Collection $capturedData;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
        $this->capturedData = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->category->getName() . ' Form';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Field>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(Field $field): static
    {
        if (!$this->fields->contains($field)) {
            $this->fields->add($field);
            $field->setForm($this);
        }

        return $this;
    }

    public function removeField(Field $field): static
    {
        if ($this->fields->removeElement($field)) {
            // set the owning side to null (unless already changed)
            if ($field->getForm() === $this) {
                $field->setForm(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CapturedData>
     */
    public function getCapturedData(): Collection
    {
        return $this->capturedData;
    }

    public function addCapturedData(CapturedData $capturedData): static
    {
        if (!$this->capturedData->contains($capturedData)) {
            $this->capturedData->add($capturedData);
            $capturedData->setForm($this);
        }

        return $this;
    }

    public function removeCapturedData(CapturedData $capturedData): static
    {
        if ($this->capturedData->removeElement($capturedData)) {
            // set the owning side to null (unless already changed)
            if ($capturedData->getForm() === $this) {
                $capturedData->setForm(null);
            }
        }

        return $this;
    }
}
