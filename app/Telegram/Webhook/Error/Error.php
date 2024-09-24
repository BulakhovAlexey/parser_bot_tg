<?php

namespace App\Telegram\Webhook\Error;

use App\Facades\Telegram;
use App\Telegram\Webhook\Webhook;

class Error extends Webhook
{
    public function run()
    {
        Telegram::message($this->chat_id, 'Что-то пошло не так.. неизвестная команда')->send();
    }
}
