<?php

namespace App\Telegram\Webhook\Commands;

use App\Facades\Telegram;
use App\Models\User;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;
use Illuminate\Support\Facades\Log;

class ChatSelect extends Webhook
{
    public const CHATS =
        [
            'WETbilisi_Didube',
            'WETbilisi_Vake',
            'WETbilisi_Saburtalo',
            'WETbilisi_Mtatsminda',
            'WETbilisi_Isani',
            'WETbilisi_Krtsanisi',
            'WETbilisi_Chugureti',
            'WETbilisi_Nadzaladevi',
            'WETbilisi_Gldani',
        ];
    public function run()
    {
        $name = $this->request->input('message')['from']['first_name'];
        InlineButton::reset();
        foreach (self::CHATS as $key => $chat) {
            InlineButton::add($chat, 'AddChat', ['chat_id' => $key], $key + 1);
        }

        return Telegram::buttons($this->chat_id, $name . ' выберите чат вашего района', InlineButton::$buttons)->send();
    }
}
