<?php

namespace App\Telegram\Webhook\Commands;

use App\Facades\Telegram;
use App\Models\User;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;
use Illuminate\Support\Facades\Log;

class Unsubscribe extends Webhook
{
    public function run()
    {
        InlineButton::reset();

        InlineButton::add('Да 😔', 'UnsubscribeAction', ['unsubscribe' => 1],  1);
        InlineButton::add('Нет 👏', 'UnsubscribeAction', ['unsubscribe' => 0],  1);

        return Telegram::buttons($this->chat_id,$this->getMessageBlade('telegram.unsubscribe.question',[]), InlineButton::$buttons)->send();
    }
}
