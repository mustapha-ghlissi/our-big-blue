<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\DenormalizationContextGroups;
use App\Enum\NormalizationContextGroups;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ApiResource(
    inputFormats: ['multipart' => ['multipart/form-data']],
    outputFormats: ['jsonld' => ['application/ld+json']],
    normalizationContext: ['groups' => [
        NormalizationContextGroups::DEFAULT,
        NormalizationContextGroups::USER,
    ]],
    denormalizationContext: ['groups' => [
        DenormalizationContextGroups::USER,
    ]],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $phoneNumber = null;

    /**
     * @var Collection<int, CapturedData>
     */
    #[ORM\OneToMany(
        targetEntity: CapturedData::class,
        mappedBy: 'user',
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    private Collection $capturedData;

    public function __construct()
    {
        $this->capturedData = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        if ($this->firstName) {
            return ucfirst($this->firstName);
        }

        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        if ($this->lastName) {
            return ucfirst($this->lastName);
        }

        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

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
            $capturedData->setUser($this);
        }

        return $this;
    }

    public function removeCapturedData(CapturedData $capturedData): static
    {
        if ($this->capturedData->removeElement($capturedData)) {
            // set the owning side to null (unless already changed)
            if ($capturedData->getUser() === $this) {
                $capturedData->setUser(null);
            }
        }

        return $this;
    }

    public function getFullName(): ?string {
        if (!$this->getFirstName()) {
            return null;
        }

        return $this->getFirstName() . ($this->getLastName() ? ' ' . $this->getLastName() : '');
    }

    public function __toString(): string
    {
        if (!$this->getFirstName()) {
            return $this->getEmail();
        }

        return $this->getFullName();
    }
}
