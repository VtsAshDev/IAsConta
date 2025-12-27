<?php

namespace App\Dto\Telegram;

class TelegramUserRequestDto
{
    private ?string $name = null;
    private ?string $phone = null;
    private ?int $telegramId = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getTelegramId(): ?int
    {
        return $this->telegramId;
    }

    public function setTelegramId(?int $telegramId): void
    {
        $this->telegramId = $telegramId;
    }
}
