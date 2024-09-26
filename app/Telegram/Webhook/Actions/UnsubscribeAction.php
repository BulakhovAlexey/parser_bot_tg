<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Webhook\Webhook;
use Illuminate\Support\Facades\Log;

class UnsubscribeAction extends Webhook
{
    public function run()
    {

        $chat_id = $this->request->input('callback_query')['from']['id'];
        $data = json_decode($this->request->input('callback_query')['data'], true);
        $answer = $data['unsubscribe'];

        $chat = Chat::where('recipient', $chat_id)->first();
        if($answer === 1){
            $chat->confirmed = 0;
            $chat->save();
            return Telegram::message($chat_id, $this->getMessageBlade('telegram.unsubscribe.confirm', []))->send();
        } else {
            $chat->confirmed = 1;
            $chat->save();
            return Telegram::message($chat_id, $this->getMessageBlade('telegram.unsubscribe.cancel', []))->send();
        }


    }

    protected function checkStreet(Chat $chat) : bool
    {
        return $chat->street == '';
    }
    protected function checkChatToParse(Chat $chat) : bool
    {
        return $chat->chat_to_parse == '';
    }

}
