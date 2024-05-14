<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\FieldRepository;
use App\Traits\TimeStampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    inputFormats: ['multipart' => ['multipart/form-data']],
    outputFormats: ['jsonld' => ['application/ld+json']],
)]
#[ORM\Entity(repositoryClass: FieldRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Field
{
    use TimeStampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['form:read', 'category:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Groups(['form:read', 'category:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Groups(['form:read', 'category:read'])]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Groups(['form:read', 'category:read'])]
    private ?string $fieldId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['form:read', 'category:read'])]
    private ?string $placeholder = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['form:read', 'category:read'])]
    private ?string $defaultValue = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    #[Groups(['form:read', 'category:read'])]
    private ?array $possibleOptions = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['form:read', 'category:read'])]
    private ?bool $required = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['form:read', 'category:read'])]
    private ?bool $multiple = null;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Form $form = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFieldId(): ?string
    {
        return $this->fieldId;
    }

    public function setFieldId(?string $fieldId): static
    {
        $this->fieldId = $fieldId;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(?string $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(?string $defaultValue): static
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getName();
    }

    public function getPossibleOptions(): ?array
    {
        return $this->possibleOptions;
    }

    public function setPossibleOptions(?array $possibleOptions): static
    {
        $this->possibleOptions = $possibleOptions;

        return $this;
    }

    public function isRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): static
    {
        $this->required = $required;

        return $this;
    }

    public function isMultiple(): ?bool
    {
        return $this->multiple;
    }

    public function setMultiple(bool $multiple): static
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(?Form $form): static
    {
        $this->form = $form;

        return $this;
    }
}
