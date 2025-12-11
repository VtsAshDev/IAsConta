<?php

namespace App\Controller;

use App\Dto\Telegram\TelegramUpdateDto;
use App\Service\AiService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/webhook', name: 'api_webhook')]
class TelegramInputController extends AbstractController
{
    public function __construct(
       private LoggerInterface $logger,
       private HttpClientInterface $httpClient,
       private AiService $aiService,
       #[Autowire(env: 'TELEGRAM_API')]
       private string $botToken
    ){
    }

    #[Route('/telegram', name: 'telegram', methods: ['POST'])]
    public function input(
        #[MapRequestPayload] TelegramUpdateDto $update
    ): Response
    {
        $message = $update->getMessage();

        if ($message && $message->getText() ) {
            $chatId = $message->getChat()->getId();
            $text = $message->getText();
            $user = $message->getUser()?->getFirstName() ?? 'Unknown';

            $this->logger->info("Telegram Payload: ", (array)$update);

            $mensagemDeVolta = "Olá, $user! Recebi sua mensagem: $text";
            $mensagemDeVolta = ($this->aiService)($message->getText());
            $this->logger->info("Log Message : $mensagemDeVolta");
            try {
                $this->httpClient->request('POST', 'https://api.telegram.org/bot' . $this->botToken . '/sendMessage', [
                    'json' => [
                        'chat_id' => $chatId,
                        'text' => $mensagemDeVolta,
                    ]
                ]);
            } catch (\Exception $e) {
                $this->logger->error("Erro ao enviar mensagem: " . $e->getMessage());
            } catch (TransportExceptionInterface $e) {
                $this->logger->error("Erro ao enviar mensagem: " . $e->getMessage());
            }
        }
        return new Response('OK', Response::HTTP_OK);
    }
}
