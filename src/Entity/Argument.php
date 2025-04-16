<?php

namespace App\Entity;

use App\Repository\ArgumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArgumentRepository::class)]
class Argument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 800)]
    private ?string $text = null;

    /**
     * @var Collection<int, Votes>
     */
    #[ORM\OneToMany(targetEntity: Votes::class, mappedBy: 'argument')]
    private Collection $votes;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'argument')]
    private Collection $reports;

    #[ORM\ManyToOne(inversedBy: 'arguments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?User $userValidate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $validationDate = null;

    #[ORM\ManyToOne(inversedBy: 'arguments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Camp $camp = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subArguments')]
    private ?self $mainArgument = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'mainArgument')]
    private Collection $subArguments;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->subArguments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @return Collection<int, Votes>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Votes $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setArgument($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getArgument() === $this) {
                $vote->setArgument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setArgument($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getArgument() === $this) {
                $report->setArgument(null);
            }
        }

        return $this;
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

    public function getUserValidate(): ?User
    {
        return $this->userValidate;
    }

    public function setUserValidate(?User $userValidate): static
    {
        $this->userValidate = $userValidate;

        return $this;
    }

    public function getValidationDate(): ?\DateTimeInterface
    {
        return $this->validationDate;
    }

    public function setValidationDate(?\DateTimeInterface $validationDate): static
    {
        $this->validationDate = $validationDate;

        return $this;
    }

    public function getCamp(): ?Camp
    {
        return $this->camp;
    }

    public function setCamp(?Camp $camp): static
    {
        $this->camp = $camp;

        return $this;
    }

    public function getMainArgument(): ?self
    {
        return $this->mainArgument;
    }

    public function setMainArgument(?self $mainArgument): static
    {
        $this->mainArgument = $mainArgument;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSubArguments(): Collection
    {
        return $this->subArguments;
    }

    public function addSubArgument(self $subArgument): static
    {
        if (!$this->subArguments->contains($subArgument)) {
            $this->subArguments->add($subArgument);
            $subArgument->setMainArgument($this);
        }

        return $this;
    }

    public function removeSubArgument(self $subArgument): static
    {
        if ($this->subArguments->removeElement($subArgument)) {
            // set the owning side to null (unless already changed)
            if ($subArgument->getMainArgument() === $this) {
                $subArgument->setMainArgument(null);
            }
        }

        return $this;
    }
}
