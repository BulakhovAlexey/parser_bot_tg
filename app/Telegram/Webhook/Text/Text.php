<?php

namespace App\Telegram\Webhook\Text;

use App\Facades\Telegram;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;

class Text extends Webhook
{
    public function run(): void
    {
        InlineButton::getDefault();
        Telegram::buttons(
            $this->chat_id,
            'Выбери команду',
            InlineButton::$buttons
        )->send();
    }

}
