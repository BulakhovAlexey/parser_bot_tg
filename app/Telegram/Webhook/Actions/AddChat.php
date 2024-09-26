<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Commands\ChatSelect;
use App\Telegram\Webhook\Webhook;

class AddChat extends Webhook
{

    public function run() : void
    {
        $data = json_decode($this->request->input('callback_query')['data'], true);
        $chat_id = $this->request->input('callback_query')['from']['id'];
        $text = $this->request->input('callback_query')['message']['text'];
        $message_id = $this->request->input('callback_query')['message']['message_id'];
        $selectedId = (int)$data['chat_id'];
        $selectedChat = ChatSelect::CHATS[$selectedId];
        $chat = Chat::updateOrCreate(['recipient' => $chat_id], [
            'recipient' => $chat_id,
            'chat_to_parse' => '@' . $selectedChat,
        ]);
        InlineButton::reset();
        if($chat){
            foreach (ChatSelect::CHATS as $key => $chat) {
                InlineButton::add($key == $selectedId ? '☑️' . $chat : $chat, 'AddChat', ['chat_id' => $key], $key + 1);
            }
            Telegram::editButtons($chat_id, $text, InlineButton::$buttons, $message_id)->send();
            Telegram::message($chat_id, (string)view('telegram.chatSelect.add', ['selectedChat' => $selectedChat]))->send();
        } else {
            Telegram::message($chat_id,  $this->getMessageBlade('chatSelect.error'))->send();
        }

    }
}
