<?php

namespace App\Builder;

use App\Entity\Category;
use App\Entity\Expense;
use App\Entity\TelegramUser;

class ExpenseBuilder
{
    public static function build(string $amout, string $description, TelegramUser $user, Category $category): Expense
    {
        return (new Expense())
            ->setAmount($amout)
            ->setDescription($description)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUser($user)
            ->setCategory($category);
    }
}
