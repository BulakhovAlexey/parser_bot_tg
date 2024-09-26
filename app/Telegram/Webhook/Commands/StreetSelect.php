<?php

namespace App\Telegram\Webhook\Commands;

use App\Facades\Telegram;
use App\Models\User;
use App\Telegram\Helpers\InlineButton;
use App\Telegram\Webhook\Realization;
use App\Telegram\Webhook\Webhook;
use Illuminate\Support\Facades\Log;

class StreetSelect extends Webhook
{
    public function run()
    {
        return Telegram::message($this->chat_id, Realization::SELECT_STREET_MESS)->send();
    }
}
