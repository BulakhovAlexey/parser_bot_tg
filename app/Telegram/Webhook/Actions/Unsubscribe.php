<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;

class UnSubscribe extends Webhook
{
    public function run(): void
    {
        InlineButton::getUnSubscribeButtons();
        Telegram::editButtons(
            $this->chat_id,
            $this->getMessageBlade('telegram.unsubscribe.question', []),
            InlineButton::$buttons,
            $this->message_id
        )->send();
    }

}
