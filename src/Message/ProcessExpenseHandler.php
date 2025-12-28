<?php

namespace App\Message;

use App\Service\AiService;
use App\Service\ExpenseService\ExpenseService;
use App\Service\TelegramService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProcessExpenseHandler {
    public function __construct(
        private AiService $aiService,
        private ExpenseService $expenseService,
        private TelegramService $telegramService,
        private LoggerInterface $logger
    ) {}

    public function __invoke(ProcessExpenseMessage $message): void
    {
        sleep(1);
        $aiData = ($this->aiService)($message->text);

        if (is_array($aiData)) {
            try {
                $respostaFinal = $this->expenseService->saveFromAi($aiData, $message->telegramUserId);
                $this->logger->error($respostaFinal);
            } catch (\Exception $e) {
                $this->logger->error("Erro ao salvar despesa: " . $e->getMessage());
                $respostaFinal = "⚠️ Tive um problema ao salvar seu gasto.";
            }
        } else {
            $respostaFinal = "Não entendi isso como um gasto.";
        }

        try {
            $this->telegramService->sendMessage($message->chatId, $respostaFinal);
        } catch (\Exception $e) {
            $this->logger->error("Erro ao enviar resposta final: " . $e->getMessage());
        }
    }
}
