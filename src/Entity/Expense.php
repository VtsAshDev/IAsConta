<?php

namespace App\Entity;

class Expense
{
    private ?int $id = null;
    private string $amount;
    private string $description;
    private \DateTimeImmutable $createdAt;
    private TelegramUser $user;
    private Category $category;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getAmount(): float
    {
        return (float) $this->amount;
    }

    public function setAmount(string|float $amount): Expense
    {
        $this->amount = (string)$amount;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Expense
    {
        $this->description = $description;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): Expense
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): Expense
    {
        $this->category = $category;
        return $this;
    }

    public function getUser(): TelegramUser
    {
        return $this->user;
    }

    public function setUser(TelegramUser $user): Expense
    {
        $this->user = $user;
        return $this;
    }
}
