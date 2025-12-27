<?php

namespace App\Entity;

class TelegramUser
{
    private ?int $id = null;


    private int $telegramId;
    private string $name;
    private ?string $phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTelegramId(): int
    {
        return $this->telegramId;
    }

    public function setTelegramId(int $telegramId): TelegramUser
    {
        $this->telegramId = $telegramId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): TelegramUser
    {
        $this->name = $name;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): TelegramUser
    {
        $this->phone = $phone;
        return $this;
    }


}
