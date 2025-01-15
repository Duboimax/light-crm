<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: \App\Repository\EmailCampaignRepository::class)]
#[ORM\Table(name: 'email_campaigns')]
class EmailCampaign
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[Groups('emailcampaign:read')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'emailCampaigns')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('emailcampaign:read')]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups('emailcampaign:read')]
    private ?string $subject = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups('emailcampaign:read')]
    private ?string $body = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups('emailcampaign:read')]
    private ?\DateTimeInterface $scheduledAt = null;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups('emailcampaign:read')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column]
    #[Groups('emailcampaign:read')]
    private ?int $mailjetId = null;

    #[ORM\Column(length: 40)]
    #[Groups('emailcampaign:read')]
    private ?string $status = null;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeInterface
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(?\DateTimeInterface $scheduledAt): self
    {
        $this->scheduledAt = $scheduledAt;

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

    public function getMailjetId(): ?int
    {
        return $this->mailjetId;
    }

    public function setMailjetId(int $mailjetId): static
    {
        $this->mailjetId = $mailjetId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
