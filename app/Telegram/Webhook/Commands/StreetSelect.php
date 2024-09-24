<?php

namespace App\Telegram\Webhook\Commands;

use App\Facades\Telegram;
use App\Models\User;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Webhook;
use Illuminate\Support\Facades\Log;

class StreetSelect extends Webhook
{
    public function run()
    {
//        $name = $this->request->input('message')['from']['first_name'];

        return Telegram::message($this->chat_id, ' test')->send();
    }
}
