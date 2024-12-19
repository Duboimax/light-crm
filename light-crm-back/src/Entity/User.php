<?php
// src/Entity/User.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: \App\Repository\UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private string $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Client::class, orphanRemoval: true)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Product::class, orphanRemoval: true)]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Sale::class, orphanRemoval: true)]
    private Collection $sales;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: EmailCampaign::class, orphanRemoval: true)]
    private Collection $emailCampaigns;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new \DateTime();
        $this->clients = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->sales = new ArrayCollection();
        $this->emailCampaigns = new ArrayCollection();
    }

    // Getters et Setters

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // garantit que chaque utilisateur a au moins ROLE_USER
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
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setUser($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getUser() === $this) {
                $client->setUser(null);
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

    public function getUserIdentifier(): string
    {
        // TODO: Implement getUserIdentifier() method.
        return $this->id;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
