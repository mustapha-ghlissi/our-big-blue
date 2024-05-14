<?php

namespace App\Traits;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

trait TimeStampableTrait
{
    #[ORM\Column(type: "date")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: "date", nullable: true)]
    private \DateTimeInterface $updatedAt;

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
