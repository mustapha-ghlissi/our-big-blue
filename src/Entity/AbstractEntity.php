<?php

namespace App\Entity;

use App\Serializer\NormalizationContextGroups;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @class AbstractEntity
 */
abstract class AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: [NormalizationContextGroups::DEFAULT])]
    protected ?int $id = null;

    #[ORM\Column(type: "date")]
    #[Groups(groups: [NormalizationContextGroups::DEFAULT])]
    protected \DateTimeInterface $createdAt;

    #[ORM\Column(type: "date", nullable: true)]
    #[Groups(groups: [NormalizationContextGroups::DEFAULT])]
    protected \DateTimeInterface $updatedAt;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = Carbon::now();
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = Carbon::now();
        return $this;
    }
}
