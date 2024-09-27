<?php

namespace App\Telegram\Webhook\Commands;

use App\Facades\Telegram;
use App\Telegram\Webhook\Webhook;

class Start extends Webhook
{
    public function run()
    {
      return Telegram::message($this->chat_id, $this->getMessageBlade('telegram.start',[]))->send();
    }
}
