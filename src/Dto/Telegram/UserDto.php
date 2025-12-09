<?php

namespace App\Dto\Telegram;

use Symfony\Component\Serializer\Attribute\SerializedName;

class UserDto
{
    public function __construct(
      private int $id,

      #[SerializedName('first_name')]
      private string $firstName,

      #[SerializedName('is_bot')]
      private bool $isBot,

      #[SerializedName('username')]
      private ?string $username = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function isBot(): bool
    {
        return $this->isBot;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}
