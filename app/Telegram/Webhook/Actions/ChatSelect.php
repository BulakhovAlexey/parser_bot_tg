<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;

class ChatSelect extends Webhook
{
    public function run(): void
    {
        InlineButton::getChatSelectButtons();
        Telegram::editButtons($this->chat_id, '⬇️ Выбирай чат ⬇️', InlineButton::$buttons, $this->message_id)->send();
    }
}
