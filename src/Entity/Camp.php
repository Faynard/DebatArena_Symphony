<?php

namespace App\Entity;

use App\Repository\CampRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampRepository::class)]
class Camp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nameCamp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCamp(): ?string
    {
        return $this->nameCamp;
    }

    public function setNameCamp(string $nameCamp): static
    {
        $this->nameCamp = $nameCamp;

        return $this;
    }
}
