<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\CapturedDataRepository;
use App\Traits\EntityIdTrait;
use App\Traits\TimeStampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
        ),
        new Delete(),
        new Put()
    ],
    normalizationContext: ['groups' => ['captured_data:read']],
    denormalizationContext: ['groups' => ['captured_data:write']]
)]
#[ORM\Entity(repositoryClass: CapturedDataRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class CapturedData
{
    use EntityIdTrait;
    use TimeStampableTrait;

    #[ORM\Column]
    #[Groups(groups: ["captured_data:read", "captured_data:write"])]
    private array $data = [];

    #[ORM\ManyToOne(inversedBy: 'capturedData')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ["captured_data:read", "captured_data:write"])]
    private ?Form $form = null;

    #[ORM\ManyToOne(inversedBy: 'capturedData')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ["captured_data:read", "captured_data:write"])]
    private ?User $user = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'capturedData', cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Groups(groups: ["captured_data:read", "captured_data:write"])]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
