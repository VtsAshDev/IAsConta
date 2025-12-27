<?php

namespace App\Dto\Telegram;

class ChatDto
{
    public function __construct(
        private ?int $id = null,
        private ?string $type = null,
    ) {
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
