<?php

namespace App\Dto\Telegram;

class ChatDto
{
    public function __construct(
        private int $id,
        private string $type,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
