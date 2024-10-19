<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;

class AddChat extends Webhook
{

    public function run(): void
    {
        $data = json_decode($this->request->input('callback_query')['data'], true);
        $selectedId = (int)$data['chat_id'];
        $selectedChat = Webhook::CHATS[$selectedId];
        $chat = Chat::updateOrCreate(['recipient' => $this->chat_id], [
            'recipient' => $this->chat_id,
            'chat_to_parse' => '@' . $selectedChat,
        ]);
        InlineButton::reset();
        if ($chat) {
            foreach (Webhook::CHATS as $key => $chat) {
                if ($key == $selectedId) {
                    InlineButton::add('✅' . $chat, 'AddChat', ['chat_id' => $key], 1);
                }
            }
            InlineButton::getBackButton(false, 2);
            Telegram::editButtons(
                $this->chat_id,
                $this->getMessageBlade('telegram.chatSelect.add', ['selectedChat' => $selectedChat]),
                InlineButton::$buttons,
                $this->message_id
            )->send();
        } else {
            Telegram::message($this->chat_id, $this->getMessageBlade('chatSelect.error', []))->send();
        }
    }
}
