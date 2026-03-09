<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'setting')]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(nullable: true)]
    private ?string $company_name = null;

    #[ORM\Column(nullable: true)]
    private ?string $reg_number = null;

    #[ORM\Column(nullable: true)]
    private ?string $contact_email = null;

    #[ORM\Column(nullable: true)]
    private ?string $contact_phone = null;

    #[ORM\Column(nullable: true)]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    private ?string $website = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $address = null;

    // Getters & Setters
    public function getId(): int { return $this->id; }

    public function getCompanyName(): ?string { return $this->company_name; }
    public function setCompanyName(?string $v): self { $this->company_name = $v; return $this; }

    public function getRegNumber(): ?string { return $this->reg_number; }
    public function setRegNumber(?string $v): self { $this->reg_number = $v; return $this; }

    public function getContactEmail(): ?string { return $this->contact_email; }
    public function setContactEmail(?string $v): self { $this->contact_email = $v; return $this; }

    public function getContactPhone(): ?string { return $this->contact_phone; }
    public function setContactPhone(?string $v): self { $this->contact_phone = $v; return $this; }

    public function getCity(): ?string { return $this->city; }
    public function setCity(?string $v): self { $this->city = $v; return $this; }

    public function getWebsite(): ?string { return $this->website; }
    public function setWebsite(?string $v): self { $this->website = $v; return $this; }

    public function getAddress(): ?string { return $this->address; }
    public function setAddress(?string $v): self { $this->address = $v; return $this; }
}