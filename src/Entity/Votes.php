<?php

namespace App\Entity;

use App\Repository\VotesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VotesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Votes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Argument $argument = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $voteDate = null;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->voteDate = new \DateTime();
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

    public function getVoteDate(): ?\DateTimeInterface
    {
        return $this->voteDate;
    }

    public function setVoteDate(\DateTimeInterface $voteDate): static
    {
        $this->voteDate = $voteDate;

        return $this;
    }
}
