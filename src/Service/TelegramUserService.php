<?php

namespace App\Service;

use App\Builder\TelegramUserBuilder;
use App\Entity\TelegramUser;
use App\Interfaces\TelegramUserRepositoryInterface;

class TelegramUserService
{
    public function __construct(
       private TelegramUserRepositoryInterface $repository,
        private TelegramUserBuilder $telegramUserBuilder
    ) {
    }

    public function saveUser(string $nome, string $phone, int $telegramId): void
    {
        $this->repository->save(($this->telegramUserBuilder)($telegramId, $nome, $phone));
    }

    public function findById(int $telegramId): ?TelegramUser
    {
       return $this->repository->findByTelegramId($telegramId);
    }
}
