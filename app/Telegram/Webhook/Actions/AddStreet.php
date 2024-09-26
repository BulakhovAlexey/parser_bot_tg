<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Webhook\Webhook;

class AddStreet extends Webhook
{

    protected string $street;
    public function run()
    {
        $chat_id = $this->request->input('message')['from']['id'];

        $this->getFormatStreet();

        if(!$this->checkLength()){
            Telegram::message($chat_id, $this->getMessageBlade('telegram.street.errorLength', []))->send();
            return false;
        }

        if(!$this->streetIsExist()){
            Telegram::message($chat_id, $this->getMessageBlade('telegram.street.errorExist', []))->send();
            return false;
        }


        $chat = Chat::updateOrCreate(['recipient' => $chat_id], [
            'recipient' => $chat_id,
            'street' => $this->street,
        ]);
        Telegram::message($chat_id,  $this->getMessageBlade('telegram.street.success', ['street' => $this->street]))->send();
    }

    protected function checkLength() : bool
    {
        return strlen($this->street) > 3;
    }
    protected function streetIsExist(): bool
    {
        return true;
        //TODO implement method
    }
    protected function getFormatStreet(): string
    {
        $street = strtolower($this->request->input('message')['text']);
        if(strpos($street, ' ') !== false){
           $streetArray = explode(' ', $street);
           foreach ($streetArray as &$item){
               $item = ucfirst($item);
           }
           $this->street = implode(' ', $streetArray);
        } else {
            $this->street = ucfirst($street);
        }
        return $this->street;
    }
}
