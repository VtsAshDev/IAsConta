<?php

namespace App\Dto\Telegram;

use Symfony\Component\Serializer\Annotation\SerializedName;

class MessageDto
{
    public function __construct(
     #[SerializedName('message_id')]
     private int $messageId,

     private ?ChatDto $chat = null,

     #[SerializedName('from')]
     private ?UserDto $user,

     private ?ContactDto $contact = null,

     private ?string $text = null,

     public ?int $date = null,
    ) {
    }

    public function getMessageId(): int
    {
        return $this->messageId;
    }

    public function getChat(): ChatDto
    {
        return $this->chat;
    }

    public function getUser(): ?UserDto
    {
        return $this->user;
    }

    public function getContact(): ?ContactDto
    {
        return $this->contact;
    }

    public function setContact(?ContactDto $contact): void
    {
        $this->contact = $contact;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getDate(): int
    {
        return $this->date;
    }
}
