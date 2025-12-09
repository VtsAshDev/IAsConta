<?php

namespace App\Dto\Telegram;

use Symfony\Component\Serializer\Annotation\SerializedName;

class MessageDto
{
    public function __construct(
     #[SerializedName('message_id')]
     private int $messageId,

     private ChatDto $chat,

     #[SerializedName('from')]
     private ?UserDto $user,

     private ?string $text = null,

     public int $date,
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getDate(): int
    {
        return $this->date;
    }
}
