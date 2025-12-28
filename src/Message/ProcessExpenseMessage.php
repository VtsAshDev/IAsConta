<?php

namespace App\Message;

class ProcessExpenseMessage
{
    public function __construct(
        public string $text,
        public int $chatId,
        public int $telegramUserId
    ) {
    }
}

