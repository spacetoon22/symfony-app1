<?php

namespace App\Entity;

use App\Repository\DossierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossierRepository::class)]
class Dossier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $cin = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $plan = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $beneficiaries = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $conditions = null;

    #[ORM\Column(nullable: true)]
    private ?float $premium = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $medicalNotes = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commercialNotes = null;

    // JSON array of doc keys that were checked e.g. ['cin','photo','medical']
    #[ORM\Column(type: 'json', nullable: true)]
    private array $documents = [];

    // JSON array of uploaded filenames stored in public/uploads/dossiers/
    #[ORM\Column(type: 'json', nullable: true)]
    private array $uploadedFiles = [];

    // Status: pending → claimed → review → approved / rejected
    #[ORM\Column(length: 20)]
    private string $status = 'pending';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $rejectionNote = null;

    // The commercial who claimed this dossier
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $claimedBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->status = 'pending';
    }
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $type = null;

    public function getType(): ?string { return $this->type; }
    public function setType(?string $v): static { $this->type = $v; return $this; }
    
    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $paymentMethod = null;

    #[ORM\Column(nullable: true)]
    private ?int $durationMonths = null;

    public function getDurationMonths(): ?int { return $this->durationMonths; }
    public function setDurationMonths(?int $v): static { $this->durationMonths = $v; return $this; }


    // ── Getters & Setters ──

    public function getId(): ?int { return $this->id; }

    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $v): static { $this->firstName = $v; return $this; }

    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(string $v): static { $this->lastName = $v; return $this; }

    public function getCin(): ?string { return $this->cin; }
    public function setCin(?string $v): static { $this->cin = $v; return $this; }

    public function getGender(): ?string { return $this->gender; }
    public function setGender(?string $v): static { $this->gender = $v; return $this; }

    public function getDob(): ?\DateTimeInterface { return $this->dob; }
    public function setDob(?\DateTimeInterface $v): static { $this->dob = $v; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $v): static { $this->email = $v; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $v): static { $this->phone = $v; return $this; }

    public function getCity(): ?string { return $this->city; }
    public function setCity(?string $v): static { $this->city = $v; return $this; }

    public function getAddress(): ?string { return $this->address; }
    public function setAddress(?string $v): static { $this->address = $v; return $this; }

    public function getPlan(): ?string { return $this->plan; }
    public function setPlan(?string $v): static { $this->plan = $v; return $this; }

    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(?\DateTimeInterface $v): static { $this->startDate = $v; return $this; }

    public function getBeneficiaries(): ?int { return $this->beneficiaries; }
    public function setBeneficiaries(?int $v): static { $this->beneficiaries = $v; return $this; }

    public function getConditions(): ?string { return $this->conditions; }
    public function setConditions(?string $v): static { $this->conditions = $v; return $this; }

    public function getPremium(): ?float { return $this->premium; }
    public function setPremium(?float $v): static { $this->premium = $v; return $this; }

    public function getMedicalNotes(): ?string { return $this->medicalNotes; }
    public function setMedicalNotes(?string $v): static { $this->medicalNotes = $v; return $this; }

    public function getCommercialNotes(): ?string { return $this->commercialNotes; }
    public function setCommercialNotes(?string $v): static { $this->commercialNotes = $v; return $this; }

    public function getDocuments(): array { return $this->documents; }
    public function setDocuments(array $v): static { $this->documents = $v; return $this; }

    public function getUploadedFiles(): array { return $this->uploadedFiles; }
    public function setUploadedFiles(array $v): static { $this->uploadedFiles = $v; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $v): static { $this->status = $v; return $this; }

    public function getRejectionNote(): ?string { return $this->rejectionNote; }
    public function setRejectionNote(?string $v): static { $this->rejectionNote = $v; return $this; }

    public function getClaimedBy(): ?User { return $this->claimedBy; }
    public function setClaimedBy(?User $v): static { $this->claimedBy = $v; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $v): static { $this->createdAt = $v; return $this; }
        
    
    // Pre-assigned by agent at submission
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $assignedTo = null;

    public function getAssignedTo(): ?User { return $this->assignedTo; }
    public function setAssignedTo(?User $v): static { $this->assignedTo = $v; return $this; }    

    public function getEndDate(): ?\DateTimeInterface { return $this->endDate; }
    public function setEndDate(?\DateTimeInterface $v): static { $this->endDate = $v; return $this; }

    public function getPaymentMethod(): ?string { return $this->paymentMethod; }
    public function setPaymentMethod(?string $v): static { $this->paymentMethod = $v; return $this; }

}