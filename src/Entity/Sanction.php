<?php

namespace App\Entity;

use App\Repository\SanctionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SanctionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Sanction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sanctionsGiven')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Argument $argument = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $sanctionDate = null;

    #[ORM\Column(length: 800)]
    private ?string $reason = null;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->sanctionDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getArgument(): ?Argument
    {
        return $this->argument;
    }

    public function setArgument(?Argument $argument): static
    {
        $this->argument = $argument;

        return $this;
    }

    public function getSanctionDate(): ?\DateTimeInterface
    {
        return $this->sanctionDate;
    }

    public function setSanctionDate(\DateTimeInterface $sanctionDate): static
    {
        $this->sanctionDate = $sanctionDate;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }
}
