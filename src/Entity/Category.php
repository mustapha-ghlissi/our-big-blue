<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CategoryRepository;
use App\Serializer\DenormalizationContextGroups;
use App\Serializer\NormalizationContextGroups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    inputFormats: ['multipart' => ['multipart/form-data']],
    outputFormats: ['jsonld' => ['application/ld+json']],
    normalizationContext: ['groups' => [
        NormalizationContextGroups::DEFAULT,
        NormalizationContextGroups::CATEGORY,
    ]],
    denormalizationContext: ['groups' => [
        DenormalizationContextGroups::CATEGORY,
    ]],
)]
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
final class Category extends AbstractEntity
{
    #[ORM\Column(length: 255)]
    #[Groups([
        NormalizationContextGroups::CATEGORY,
        DenormalizationContextGroups::CATEGORY,
        NormalizationContextGroups::CAPTURED_DATA
    ])]
    private ?string $name = null;

    #[ORM\OneToOne(targetEntity: Form::class, mappedBy: 'category', cascade: ['persist', 'remove'])]
    #[Groups([NormalizationContextGroups::CATEGORY])]
    private ?Form $form = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function setForm(?Form $form): void
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->getName();
    }
}
