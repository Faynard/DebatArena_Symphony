<?php

namespace App\Entity;

use App\Repository\DebateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DebateRepository::class)]
class Debate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nameDebate = null;

    #[ORM\Column(length: 800)]
    private ?string $descriptionDebate = null;

    #[ORM\Column]
    private ?bool $isValid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameDebate(): ?string
    {
        return $this->nameDebate;
    }

    public function setNameDebate(string $nameDebate): static
    {
        $this->nameDebate = $nameDebate;

        return $this;
    }

    public function getDescriptionDebate(): ?string
    {
        return $this->descriptionDebate;
    }

    public function setDescriptionDebate(string $descriptionDebate): static
    {
        $this->descriptionDebate = $descriptionDebate;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): static
    {
        $this->isValid = $isValid;

        return $this;
    }
}
