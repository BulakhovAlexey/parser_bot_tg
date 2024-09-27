<?php

namespace App\Telegram\Webhook\Commands;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Models\User;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;
use Illuminate\Support\Facades\Log;

class Subscribe extends Webhook
{
    public function run()
    {
        $name = $this->request->input('message')['from']['first_name'];
        $chat = Chat::where('recipient', $this->chat_id)->first();
        if($chat) {
            $params = [
                'name' => $name,
                'chat_to_parse' => $chat->chat_to_parse,
                'street' => $chat->street,
            ];
        } else {
            $params = [
                'name' => $name,
            ];
        }

        InlineButton::reset();

        InlineButton::add('👉 Подписаться 👈', 'SubscribeAction', ['subscribe' => 1],  1);

        return Telegram::buttons($this->chat_id,  $this->getMessageBlade('telegram.subscribe.confirm', $params), InlineButton::$buttons)->send();
    }
}
