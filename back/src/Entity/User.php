<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    #[Groups(['user:read'])]
    private string $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    private string $email;

    #[Ignore]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;
    
    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['user:read'])]
    private ?string $firstname;

    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['user:read'])]
    private ?string $lastname;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:read'])]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(['user:write'])]
    private string $password;

    #[Ignore]
    #[ORM\OneToMany(targetEntity: Customer::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $customers;

    #[Ignore]
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $products;

    #[Ignore]
    #[ORM\OneToMany(targetEntity: Sale::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $sales;

    #[Ignore]
    #[ORM\OneToMany(targetEntity: EmailCampaign::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $emailCampaigns;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups('user:read')]
    private \DateTimeInterface $createdAt;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $services;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;


    public function __construct()
    {
        $this->id = Uuid::v7()->toRfc4122();
        $this->createdAt = new \DateTime();
        $this->customers = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->sales = new ArrayCollection();
        $this->emailCampaigns = new ArrayCollection();
        $this->services = new ArrayCollection();
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

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * A visual identifier that represents this user.
     */

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Garantit que chaque utilisateur a au moins ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    #[Ignore]
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // Pas nécessaire pour les algorithmes modernes
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // Supprimez les données temporaires si nécessaire
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setUser($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getUser() === $this) {
                $customer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sale[]
     */
    public function getSales(): Collection
    {
        return $this->sales;
    }

    public function addSale(Sale $sale): self
    {
        if (!$this->sales->contains($sale)) {
            $this->sales[] = $sale;
            $sale->setUser($this);
        }

        return $this;
    }

    public function removeSale(Sale $sale): self
    {
        if ($this->sales->removeElement($sale)) {
            // set the owning side to null (unless already changed)
            if ($sale->getUser() === $this) {
                $sale->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getEmailCampaigns(): Collection
    {
        return $this->emailCampaigns;
    }

    public function addEmailCampaign(EmailCampaign $emailCampaign): self
    {
        if (!$this->emailCampaigns->contains($emailCampaign)) {
            $this->emailCampaigns[] = $emailCampaign;
            $emailCampaign->setUser($this);
        }

        return $this;
    }

    public function removeEmailCampaign(EmailCampaign $emailCampaign): self
    {
        if ($this->emailCampaigns->removeElement($emailCampaign)) {
            // set the owning side to null (unless already changed)
            if ($emailCampaign->getUser() === $this) {
                $emailCampaign->setUser(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setOwner($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getOwner() === $this) {
                $service->setOwner(null);
            }
        }

        return $this;
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

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
}
