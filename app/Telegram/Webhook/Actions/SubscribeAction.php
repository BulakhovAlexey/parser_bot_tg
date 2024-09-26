<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Webhook\Webhook;
use Illuminate\Support\Facades\Log;

class SubscribeAction extends Webhook
{
    public function run()
    {
        $chat_id = $this->request->input('callback_query')['from']['id'];
        $chat = Chat::where('recipient', $chat_id)->first();

        if($chat){

            if($this->checkChatToParse($chat)){
                return Telegram::message($chat_id, $this->getMessageBlade('telegram.subscribe.chatError',[]))->send();
            }

            if($this->checkStreet($chat)){
                return Telegram::message($chat_id, $this->getMessageBlade('telegram.subscribe.streetError',[]))->send();
            }

            if($chat->confirmed === 1){
                return Telegram::message($chat_id, $this->getMessageBlade('telegram.subscribe.isExist',[]))->send();
            }

            $chat->confirmed = 1;
            $chat->save();
            return Telegram::message($chat_id, $this->getMessageBlade('telegram.subscribe.success',[]))->send();

        } else {
            return Telegram::message($chat_id, $this->getMessageBlade('telegram.subscribe.error',[]))->send();
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
