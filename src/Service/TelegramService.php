<?php

namespace App\Service;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class TelegramService
{
    public function __construct(
       private HttpClientInterface $httpClient,
       private string $telegramToken
    ) {
    }

    private function getBaseUrl():string
    {
        return "https://api.telegram.org/bot" . $this->telegramToken;
    }

    public function sendMessage(int $chatId, string $text): void
    {
        $this->httpClient->request('POST', $this->getBaseUrl() . '/sendMessage', [
            'json' => [
                'chat_id' => $chatId,
                'text' => $text,
            ],
        ]);
    }

    public function requestContact(int $chatId, string $message = "Por favor, compartilhe seu contato para continuar:"): void
    {
        $this->httpClient->request('POST', $this->getBaseUrl() . '/sendMessage', [
            'json' => [
                'chat_id' => $chatId,
                'text' => $message,
                'reply_markup' => [
                    'keyboard' => [
                        [
                            [
                                'text' => "📱 Compartilhar Telefone para cadastrar-se",
                                'request_contact' => true
                            ]
                        ]
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ],
            ],
        ]);
    }
}
