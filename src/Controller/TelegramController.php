<?php

namespace App\Controller;

use App\Builder\TelegramUserBuilder;
use App\Dto\Telegram\TelegramUpdateDto;
use App\Message\ProcessExpenseMessage;
use App\Repository\TelegramUserRepository;
use App\Service\TelegramService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/webhook', name: 'api_webhook', methods: ['POST', 'GET'])]
class TelegramController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger,
        private TelegramService $telegramService,
        private TelegramUserRepository $repository,
    ) {
    }

    #[Route('/telegram', name: 'telegram', methods: ['POST'])]
    public function input(
        #[MapRequestPayload] TelegramUpdateDto $update,
        MessageBusInterface $bus
    ): Response {
        $message = $update->getMessage();

        if (!$message) {
            return new Response('OK', Response::HTTP_OK);
        }

        $chatId = $message->getChat()->getId();
        $telegramUserId = $message->getUser()->getId();

        if ($contact = $message->getContact()) {

            if ($contact->getUserId() !== $telegramUserId) {
                $this->telegramService->sendMessage($chatId, "Por favor, compartilhe o seu próprio contato usando o botão abaixo.");
                return new Response('OK', Response::HTTP_OK);
            }

            if (!$this->repository->findByTelegramId($telegramUserId)) {
                $user = (
                    TelegramUserBuilder::build(
                    $contact->getFirstName(),
                    $contact->getPhoneNumber(),
                    $telegramUserId)
                );

                $this->repository->save($user);
                $this->telegramService->sendMessage($chatId, "Cadastro realizado com sucesso! Agora você pode enviar seus gastos.");
            } else {
                $this->telegramService->sendMessage($chatId, "Seus dados já estão atualizados.");
            }

            return new Response('OK', Response::HTTP_OK);
        }

        if (!$this->repository->findOneByTelegramId($telegramUserId)) {
            $this->telegramService->sendMessage($chatId, "Olá! Para começarmos, preciso que você se identifique.");
            $this->telegramService->requestContact($chatId);
            return new Response('OK', Response::HTTP_OK);
        }

        if ($text = $message->getText()) {
            $userName = $message->getUser()?->getFirstName() ?? 'Usuário';

            $this->logger->info("Mensagem recebida de $userName: $text");

            if ($text = $message->getText()) {

                $bus->dispatch(new ProcessExpenseMessage(
                    $text,
                    $chatId,
                    $telegramUserId
                ));

                $this->telegramService->sendMessage($chatId, "Estou processando seu gasto... ⏳");
            }
        }

        return new Response('OK', Response::HTTP_OK);
    }
}
