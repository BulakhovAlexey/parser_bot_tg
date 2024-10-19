<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;

class Subscribe extends Webhook
{

    public function run(): void
    {
        $message = $this->getSubscribeMessage();
        InlineButton::getBackButton();
        Telegram::editButtons(
            $this->chat_id,
            $message,
            InlineButton::$buttons,
            $this->message_id
        )->send();
    }

    public function getSubscribeMessage(): string
    {
        $chat = Chat::where('recipient', $this->chat_id)->first();
        if ($chat) {
            if ($chat->chat_to_parse == '' || $chat->street == '') {
                return $this->getMessageBlade('telegram.subscribe.emptyFields', []);
            }

            if ($chat->confirmed) {
                return $this->getMessageBlade('telegram.subscribe.isExist', []);
            } else {
                $chat->confirmed = true;
                $chat->save();
                return $this->getMessageBlade('telegram.subscribe.success', [
                    'chat_to_parse' => $chat->chat_to_parse,
                    'street' => $chat->street
                ]);
            }
        } else {
            return $this->getMessageBlade('telegram.subscribe.error', []);
        }
    }
}
