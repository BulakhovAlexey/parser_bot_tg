<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;

class ChatsShow extends Webhook
{
    public function run()
    {
        InlineButton::getBackButton();
        Telegram::editButtons(
            $this->chat_id,
            $this->getMessageBlade('telegram.chats', ['chats' => Webhook::CHATS_DESCRIPTION]),
            InlineButton::$buttons,
            $this->message_id
        )->send();
    }
}
