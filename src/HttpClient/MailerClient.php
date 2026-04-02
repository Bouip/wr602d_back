<?php

namespace App\HttpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class MailerClient
{
    public function __construct(
        private HttpClientInterface $client,
        private string $mailerUrl,
        private string $mailerApiKey,
        private string $mailerApiKeyValue,
    ) {}

    public function sendEmail(string $to, string $subject, string $message): void
    {
        $this->client->request('POST', $this->mailerUrl . '/send-mail', [
            'headers' => [
                $this->mailerApiKey => $this->mailerApiKeyValue,
            ],
            'json' => [
                'to' => $to,
                'subject' => $subject,
                'message' => $message,
            ],
        ]);
    }
}
