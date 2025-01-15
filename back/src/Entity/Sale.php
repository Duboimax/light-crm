<?php

namespace App\Entity;

use App\Enum\SaleStatus;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SaleRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    #[Groups('sale:read')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('sale:read')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Customer::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('sale:read')]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('sale:read')]
    private ?Service $service = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups('sale:read')]
    private \DateTimeInterface $saleDate;

    #[ORM\Column(type: 'float')]
    #[Groups('sale:read')]
    private float $total;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups('sale:read')]
    private ?float $discount = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('sale:read')]
    private ?string $comment = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Groups('sale:read')]
    private string $status = SaleStatus::PENDING->value;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups('sale:read')]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups('sale:read')]
    private ?\DateTimeInterface $paymentDate = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups('sale:read')]
    private ?float $subtotal = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups('sale:read')]
    private ?float $tax = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('sale:read')]
    private ?string $transactionReference = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('sale:read')]
    private ?string $billingAddress = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Groups('sale:read')]
    private ?string $invoiceNumber = null;

    public function __construct()
    {
        $this->id = Uuid::v7()->toRfc4122();
        $this->saleDate = new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getSaleDate(): \DateTimeInterface
    {
        return $this->saleDate;
    }

    public function setSaleDate(\DateTimeInterface $saleDate): self
    {
        $this->saleDate = $saleDate;

        return $this;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(SaleStatus $status): self
    {
        $this->status = $status->value;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?\DateTimeInterface $paymentDate): self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    public function setSubtotal(?float $subtotal): self
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(?float $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getTransactionReference(): ?string
    {
        return $this->transactionReference;
    }

    public function setTransactionReference(?string $transactionReference): self
    {
        $this->transactionReference = $transactionReference;

        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?string $billingAddress): self
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(?string $invoiceNumber): self
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }
}
