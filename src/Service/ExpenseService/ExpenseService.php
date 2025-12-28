<?php

namespace App\Service\ExpenseService;

use App\Builder\ExpenseBuilder;
use App\Entity\Expense;
use App\Interfaces\TelegramUserRepositoryInterface;
use App\Repository\CategoryRepository;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TelegramUserRepositoryInterface $telegramUserRepository,
        private CategoryRepository $categoryRepository
    ) {
    }

    public function saveFromAi(array $aiData, int $telegramUserId): string
    {
        try {
            $this->entityManager->beginTransaction();

            $user = $this->telegramUserRepository->findByTelegramId($telegramUserId);

            $category = $this->categoryRepository->findOneByName($aiData['categoria']);

            $expense = ExpenseBuilder::build(
                $aiData['valor'],
                $aiData['descricao'],
                $user,
                $category
            );

            $this->entityManager->persist($expense);
            $this->entityManager->flush();

            $this->entityManager->commit();

           return $this->getEncouragingMessage($aiData,$category);
        }
        catch (\Exception $exception){
            $this->entityManager->rollback();
            throw $exception;
        }
    }

    private function getEncouragingMessage(array $aiData, $category): string
    {
        $valorFormatado = "R$ " . number_format((float)$aiData['valor'], 2, ',', '.');
        $categoriaFormatada = ($category->getIcon() ?? '💰') . " " . $category->getName();

        $frases = [
            "Mais um passo para sua organização! ✨",
            "Mandou bem! Registro feito com sucesso. 🚀",
            "Tudo sob controle por aqui! ✅",
            "Anotadíssimo! Consciência financeira é tudo. 👏",
            "Gasto devidamente guardado na pasta. 💪"
        ];

        $fraseSorteada = $frases[array_rand($frases)];

        return sprintf(
            "%s\n" .
            "<b>Categoria:</b> %s\n" .
            "<b>Valor:</b> %s",
            $fraseSorteada,
            $categoriaFormatada,
            $valorFormatado
        );
    }
}
