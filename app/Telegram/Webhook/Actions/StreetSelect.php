<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Telegram\Webhook\Webhook;
use Illuminate\Support\Facades\Cache;

class StreetSelect extends Webhook
{
    public function run(): void
    {
        Telegram::editMessage(
            $this->chat_id,
            'Напишите название улицы, например <b>Godziashvili</b>',
            $this->message_id
        )->send();
        Cache::put(env('STREET_SELECT_CACHE_KEY', 'street-select-') . $this->chat_id, 'Y', now()->addMinutes(20));
    }
}
