<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            openapi:           new Model\Operation(
                requestBody: new Model\RequestBody(
                      content: new \ArrayObject([
                            'multipart/form-data' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'file' => [
                                            'type' => 'string',
                                            'format' => 'binary'
                                        ]
                                    ]
                                ]
                            ]
                      ])
                )
            )
        )
    ],
    inputFormats: ['multipart' => ['multipart/form-data']],
    outputFormats: ['jsonld' => ['application/ld+json']],
    normalizationContext: ['groups' => ['image:read']],
    denormalizationContext: ['groups' => ['image:write']],
)]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ["image:read", "captured_data:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ["image:read", "captured_data:read"])]
    public ?string $uri = null;

    #[Vich\UploadableField(mapping: 'images', fileNameProperty: 'uri', originalName: 'name')]
    #[Groups(groups: ["image:write", "captured_data:write"])]
    #[Assert\NotBlank]
    public ?File $file = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ["image:read", "captured_data:read"])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ["image:write"])]
    private ?CapturedData $capturedData = null;

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(?string $uri): static
    {
        $this->uri = $uri;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCapturedData(): ?CapturedData
    {
        return $this->capturedData;
    }

    public function setCapturedData(?CapturedData $capturedData): static
    {
        $this->capturedData = $capturedData;

        return $this;
    }
}
