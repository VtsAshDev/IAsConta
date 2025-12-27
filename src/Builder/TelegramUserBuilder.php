<?php

namespace App\Builder;

use App\Entity\TelegramUser;

class TelegramUserBuilder
{
    public static function build(string $name, string $phone, int $telegramId): TelegramUser
    {
       return (new TelegramUser())
           ->setName($name)
           ->setPhone($phone)
           ->setTelegramId($telegramId);
    }
}
