<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;

    #[ORM\Column(length: 320)]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 150)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    /**
     * @var Collection<int, Votes>
     */
    #[ORM\OneToMany(targetEntity: Votes::class, mappedBy: 'user')]
    private Collection $votes;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'user')]
    private Collection $reports;

    /**
     * @var Collection<int, Argument>
     */
    #[ORM\OneToMany(targetEntity: Argument::class, mappedBy: 'user')]
    private Collection $arguments;

    /**
     * @var Collection<int, Sanction>
     */
    #[ORM\OneToMany(targetEntity: Sanction::class, mappedBy: 'user')]
    private Collection $sanctionsGiven;

    #[ORM\Column]
    private ?bool $isBanned = null;

    /**
     * @var Collection<int, Debate>
     */
    #[ORM\OneToMany(targetEntity: Debate::class, mappedBy: 'userCreated')]
    private Collection $debates;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdDate = new \DateTime();
        $this->roles = ['ROLE_USER'];
        $this->isBanned = false;
    }

    public function __construct()
    {
        $this->votes = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->arguments = new ArrayCollection();
        $this->sanctionsGiven = new ArrayCollection();
        $this->debates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
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
            $vote->setUser($this);
        }

        return $this;
    }

    public function removeVote(Votes $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getUser() === $this) {
                $vote->setUser(null);
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
            $report->setUser($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getUser() === $this) {
                $report->setUser(null);
            }
        }

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
            $argument->setUser($this);
        }

        return $this;
    }

    public function removeArgument(Argument $argument): static
    {
        if ($this->arguments->removeElement($argument)) {
            // set the owning side to null (unless already changed)
            if ($argument->getUser() === $this) {
                $argument->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sanction>
     */
    public function getSanctionsGiven(): Collection
    {
        return $this->sanctionsGiven;
    }

    public function addSanctionsGiven(Sanction $sanctionsGiven): static
    {
        if (!$this->sanctionsGiven->contains($sanctionsGiven)) {
            $this->sanctionsGiven->add($sanctionsGiven);
            $sanctionsGiven->setUser($this);
        }

        return $this;
    }

    public function removeSanctionsGiven(Sanction $sanctionsGiven): static
    {
        if ($this->sanctionsGiven->removeElement($sanctionsGiven)) {
            // set the owning side to null (unless already changed)
            if ($sanctionsGiven->getUser() === $this) {
                $sanctionsGiven->setUser(null);
            }
        }

        return $this;
    }

    public function isBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): static
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    /**
     * @return Collection<int, Debate>
     */
    public function getDebates(): Collection
    {
        return $this->debates;
    }

    public function addDebate(Debate $debate): static
    {
        if (!$this->debates->contains($debate)) {
            $this->debates->add($debate);
            $debate->setUserCreated($this);
        }

        return $this;
    }

    public function removeDebate(Debate $debate): static
    {
        if ($this->debates->removeElement($debate)) {
            // set the owning side to null (unless already changed)
            if ($debate->getUserCreated() === $this) {
                $debate->setUserCreated(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function eraseCredentials()
    {
        
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    
}
