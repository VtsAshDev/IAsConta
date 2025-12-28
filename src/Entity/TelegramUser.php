<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TelegramUser
{
    private ?int $id = null;


    private int $telegramId;
    private string $name;
    private ?string $phone = null;
    private Collection $expenses;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
    }

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

    public function getExpenses(): Collection
    {
        return $this->expenses;
    }
}
