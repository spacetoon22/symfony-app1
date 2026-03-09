<?php

namespace App\Entity;

use App\Repository\ProspectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProspectRepository::class)]
class Prospect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $firstname = null;

    #[ORM\Column(length: 10)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?int $phone = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $cin = null;

    #[ORM\Column(length: 15)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTime $createdAT = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'prospects')]
    private Collection $assingedTo;

    public function __construct()
    {
        $this->assingedTo = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->status = 'NEW';
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAT(): ?\DateTime
    {
        return $this->createdAT;
    }

    public function setCreatedAT(\DateTime $createdAT): static
    {
        $this->createdAT = $createdAT;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAssingedTo(): Collection
    {
        return $this->assingedTo;
    }

    public function addAssingedTo(User $assingedTo): static
    {
        if (!$this->assingedTo->contains($assingedTo)) {
            $this->assingedTo->add($assingedTo);
        }

        return $this;
    }

    public function removeAssingedTo(User $assingedTo): static
    {
        $this->assingedTo->removeElement($assingedTo);

        return $this;
    }
}