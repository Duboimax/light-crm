<?php

namespace App\Service;

use Mailjet\Client;

class MailjetService
{

    public function __construct(
        private readonly string $publicKey,
        private readonly string $privateKey
    )
    {}

    public function getClient(): Client
    {
        return new Client($this->publicKey, $this->privateKey, true, ['version' => 'v3']);
    }
}

