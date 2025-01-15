<?php

namespace App\Service\Mailer;

class MailerBuilder
{
    public function createWelcomeEmailConfig(string $recipientEmail, string $recipientName): EmailConfigInterface
    {
        return new EmailConfig(
            $recipientEmail,
            $recipientName,
            6635391, 
            ['nom' => $recipientName],
            'Bienvenue chez nous !'
        );
    }
}
