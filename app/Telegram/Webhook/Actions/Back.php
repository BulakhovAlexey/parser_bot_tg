<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;

class Back extends Webhook
{

    public const MESSAGE = 'Выбери команду';

    public function run(): void
    {
        InlineButton::getDefault();
        Telegram::editButtons($this->chat_id, self::MESSAGE, InlineButton::$buttons, $this->message_id)->send();
    }
}
