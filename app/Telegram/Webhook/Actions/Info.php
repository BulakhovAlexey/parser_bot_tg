<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;

class Info extends Webhook
{

    public function run(): void
    {
        InlineButton::getBackButton();
        Telegram::editButtons($this->chat_id, $this->getInfoText(), InlineButton::$buttons, $this->message_id)->send();
    }

    public function getInfoText(): string
    {
        $chat = Chat::where('recipient', $this->chat_id)->first();
        $text = 'Подписка не найдена';
        if ($chat) {
            $text = $this->getMessageBlade('telegram.subscribe.info', [
                'chat_to_parse' => $chat->chat_to_parse,
                'street' => $chat->street,
                'active' => $chat->confirmed ? 'Да' : 'Нет',
            ]);
        };

        return $text;
    }
}
