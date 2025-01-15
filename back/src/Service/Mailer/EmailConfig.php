<?php

namespace App\Service\Mailer;

class EmailConfig implements EmailConfigInterface {

    public function __construct(
        private readonly string $recipientEmail,
        private readonly string $recipientName,
        private readonly int $templateId,
        private readonly array $variables,
        private readonly string $subject
    ) {}

    public function getRecipientEmail(): string
    {
        return $this->recipientEmail;
    }

    public function getRecipientName(): string
    {
        return $this->recipientName;
    }

    public function getTemplateId(): int
    {
        return $this->templateId;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }
}

