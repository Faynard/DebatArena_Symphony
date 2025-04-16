<?php

namespace App\Entity;

use App\Repository\CampRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Argument>
     */
    #[ORM\OneToMany(targetEntity: Argument::class, mappedBy: 'camp')]
    private Collection $arguments;

    #[ORM\ManyToOne(inversedBy: 'camps')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Debate $debate = null;

    public function __construct()
    {
        $this->arguments = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Argument>
     */
    public function getArguments(): Collection
    {
        return $this->arguments;
    }

    public function addArgument(Argument $argument): static
    {
        if (!$this->arguments->contains($argument)) {
            $this->arguments->add($argument);
            $argument->setCamp($this);
        }

        return $this;
    }

    public function removeArgument(Argument $argument): static
    {
        if ($this->arguments->removeElement($argument)) {
            // set the owning side to null (unless already changed)
            if ($argument->getCamp() === $this) {
                $argument->setCamp(null);
            }
        }

        return $this;
    }

    public function getDebate(): ?Debate
    {
        return $this->debate;
    }

    public function setDebate(?Debate $debate): static
    {
        $this->debate = $debate;

        return $this;
    }
}
