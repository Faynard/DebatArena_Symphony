<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nameCategory = null;

    #[ORM\Column(length: 800)]
    private ?string $descriptionCategory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCategory(): ?string
    {
        return $this->nameCategory;
    }

    public function setNameCategory(string $nameCategory): static
    {
        $this->nameCategory = $nameCategory;

        return $this;
    }

    public function getDescriptionCategory(): ?string
    {
        return $this->descriptionCategory;
    }

    public function setDescriptionCategory(string $descriptionCategory): static
    {
        $this->descriptionCategory = $descriptionCategory;

        return $this;
    }
}
