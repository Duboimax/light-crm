<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: \App\Repository\CustomerRepository::class)]
#[ORM\Table(name: 'customers')]
class Customer
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[Groups(['customer:read', 'sale:read'])]
    private string $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['customer:read', 'sale:read'])]
    private ?string $firstname = null;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['customer:read', 'sale:read'])]
    private ?string $lastname = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['customer:read', 'sale:read'])]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    #[Groups(['customer:read', 'sale:read'])]
    private ?string $phone = null;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['customer:read', 'sale:read'])]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: Address::class, cascade: ["persist"], inversedBy: "customers")]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['customer:read', 'sale:read'])]
    private ?Address $address = null;

    public function __construct()
    {
        $this->id = Uuid::v7()->toRfc4122();
        $this->createdAt = new \DateTime();
    }

    // Getters et Setters

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User | null $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

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
}
