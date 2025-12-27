<?php

namespace App\Interfaces;

use App\Entity\TelegramUser;

interface TelegramUserRepositoryInterface
{
    public function save(TelegramUser $user): void;

    public function findByTelegramId(string $telegramId): ?TelegramUser;
}
