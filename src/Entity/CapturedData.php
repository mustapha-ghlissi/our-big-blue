<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CapturedDataRepository;
use App\Serializer\DenormalizationContextGroups;
use App\Serializer\NormalizationContextGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    inputFormats: ['multipart' => ['multipart/form-data']],
    outputFormats: ['jsonld' => ['application/ld+json']],
    normalizationContext: ['groups' => [
        NormalizationContextGroups::DEFAULT,
        NormalizationContextGroups::CAPTURED_DATA,
    ]],
    denormalizationContext: ['groups' => [
        DenormalizationContextGroups::CAPTURED_DATA,
    ]]
)]
#[Post()]
#[Put()]
#[Patch()]
#[Delete()]
#[Get(security: "is_granted('ROLE_ADMIN') or object.user == user")]
#[GetCollection(security: "is_granted('ROLE_ADMIN') or object.user == user")]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ORM\Entity(repositoryClass: CapturedDataRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CapturedData extends AbstractEntity
{
    #[ORM\ManyToOne(inversedBy: 'capturedData')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        NormalizationContextGroups::CAPTURED_DATA,
        DenormalizationContextGroups::CAPTURED_DATA,
    ])]
    private ?Form $form = null;

    #[ORM\ManyToOne(inversedBy: 'capturedData')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups([
        NormalizationContextGroups::CAPTURED_DATA,
        DenormalizationContextGroups::CAPTURED_DATA,
    ])]
    private ?User $user = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'capturedData', cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Groups([
        NormalizationContextGroups::CAPTURED_DATA,
        DenormalizationContextGroups::CAPTURED_DATA,
    ])]
    private Collection $images;

    #[ORM\Column]
    #[Groups([
        NormalizationContextGroups::CAPTURED_DATA,
        DenormalizationContextGroups::CAPTURED_DATA,
    ])]
    private array $data = [];

    public function __construct()
    {
        $this->images = new ArrayCollection();
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setCapturedData($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getCapturedData() === $this) {
                $image->setCapturedData(null);
            }
        }

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
