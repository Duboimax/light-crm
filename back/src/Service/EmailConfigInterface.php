<?php

namespace App\Service;

interface EmailConfigInterface {
    public function getRecipientEmail(): string;
    public function getRecipientName(): string;
    public function getTemplateId(): int;
    public function getSubject(): string;
    public function getVariables(): array;
}

