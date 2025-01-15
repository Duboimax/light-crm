<?php

namespace App\Service;

use Mailjet\Resources;
use App\Service\Mailer\EmailConfigInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class MailerService
{
    public function __construct(
        private readonly MailjetService $mailjetService,
        #[Autowire('%admin_email%')]
        private string $adminEmail,
    ) {
    }

    public function sendEmail(EmailConfigInterface $config): void
    {
        $client = $this->mailjetService->getClient();

        $body = [
            'FromEmail' => $this->adminEmail,
            'FromName' => 'Light CRM',
            'To' => sprintf('"%s" <%s>', $config->getRecipientName(), $config->getRecipientEmail()),
            'Subject' => $config->getSubject(),
            'Mj-TemplateID' => $config->getTemplateId(),
            'Mj-TemplateLanguage' => true,
            'Vars' => json_encode($config->getVariables()),
        ];

        $response = $client->post(Resources::$Email, ['body' => $body]);

        if (!$response->success()) {
            throw new \RuntimeException(sprintf(
                'Failed to send email to %s. Status: %d. Error: %s',
                $config->getRecipientEmail(),
                $response->getStatus(),
                $response->getBody()
            ));
        }
    }
}
