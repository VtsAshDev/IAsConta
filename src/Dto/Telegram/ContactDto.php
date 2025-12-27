<?php

namespace App\Dto\Telegram;

class ContactDto
{
    public function __construct(
        private string $phone_number,
        private string $first_name,
        private ?int $user_id = null
    ) {}

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }
}
