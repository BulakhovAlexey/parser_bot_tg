<?php

namespace App\Telegram\Webhook\Actions;

use App\Facades\Telegram;
use App\Models\Chat;
use App\ParserClient\TelegramClient;
use App\Telegram\Webhook\Webhook;

class AddStreet extends Webhook
{

    protected string $street;
    protected TelegramClient $tgClient;
    protected int $counter;
    public function run()
    {
        $chat_id = $this->request->input('message')['from']['id'];

        $this->getFormatStreet();

        if(!$this->checkLength()){
            return Telegram::message($chat_id, $this->getMessageBlade('telegram.street.errorLength', []))->send();
        }

        $this->getStreetCounter();


        if($this->counter < 2){
            return Telegram::message($chat_id, $this->getMessageBlade('telegram.street.errorExist', ['counter' => $this->counter, 'street' => $this->street]))->send();
        }

        Telegram::message($chat_id, $this->getMessageBlade('telegram.street.counter', ['counter' => $this->counter, 'street' => $this->street]))->send();


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
    protected function getStreetCounter(): int
    {
        $data = json_decode(file_get_contents(public_path('chat_histories.json')), true);
        $this->counter = 0;
        foreach ($data as $message) {
            if(strpos($message, ' ' . $this->street . ' ') !== false || strpos($message, $this->street . ',') !== false){
                $this->counter++;
            }
        }
        return $this->counter ;
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
