<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class MailerService
{
    public function __construct(
        private Client $mailjetClient,
        #[Autowire('%admin_email%')]
        private string $adminEmail,
        #[Autowire('%mailjet.api_key%')]
        private string $apiKey,
        #[Autowire('%mailjet.secret_key%')]
        private string $secretKey
    ) {
        $this->mailjetClient = new Client($this->apiKey, $this->secretKey, true, ['version' => 'v3.1']);
    }

    public function sendEmail(EmailConfigInterface $config): void
    {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $this->adminEmail,
                        'Name' => 'Light CRM',
                    ],
                    'To' => [
                        [
                            'Email' => $config->getRecipientEmail(),
                            'Name' => $config->getRecipientName(),
                        ],
                    ],
                    'TemplateID' => $config->getTemplateId(),
                    'TemplateLanguage' => true,
                    'Subject' => $config->getSubject(),
                    'Variables' => $config->getVariables(),
                ],
            ],
        ];

        $response = $this->mailjetClient->post(Resources::$Email, ['body' => $body]);

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
