<?php

namespace App\Dto\Telegram;

use Symfony\Component\Serializer\Annotation\SerializedName;

readonly class TelegramUpdateDto
{
    public function __construct(
        #[SerializedName('update_id')]
        private int $updateId,

        private ?MessageDto $message = null,
    ) {
    }

    public function getMessage(): ?MessageDto
    {
        return $this->message;
    }

    public function getUpdateId(): int
    {
        return $this->updateId;
    }
}
