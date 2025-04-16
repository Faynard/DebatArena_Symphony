<?php

namespace App\Entity;

use App\Repository\DebateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    /**
     * @var Collection<int, Camp>
     */
    #[ORM\OneToMany(targetEntity: Camp::class, mappedBy: 'debate')]
    private Collection $camps;

    #[ORM\ManyToOne(inversedBy: 'debates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userCreated = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class)]
    private Collection $categories;

    public function __construct()
    {
        $this->camps = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Camp>
     */
    public function getCamps(): Collection
    {
        return $this->camps;
    }

    public function addCamp(Camp $camp): static
    {
        if (!$this->camps->contains($camp)) {
            $this->camps->add($camp);
            $camp->setDebate($this);
        }

        return $this;
    }

    public function removeCamp(Camp $camp): static
    {
        if ($this->camps->removeElement($camp)) {
            // set the owning side to null (unless already changed)
            if ($camp->getDebate() === $this) {
                $camp->setDebate(null);
            }
        }

        return $this;
    }

    public function getUserCreated(): ?User
    {
        return $this->userCreated;
    }

    public function setUserCreated(?User $userCreated): static
    {
        $this->userCreated = $userCreated;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
