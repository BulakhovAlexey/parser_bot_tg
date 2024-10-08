<?php

namespace App\Telegram\Webhook;

use App\Facades\Telegram;
use App\Telegram\Bot\Factory;
use Illuminate\Http\Request;

class Webhook
{
    protected Request $request;
    protected $chat_id;
    public const CHATS =
        [
            'WETbilisi_Didube',
            'WETbilisi_Vake',
            'WETbilisi_Saburtalo',
            'WETbilisi_Mtatsminda',
            'WETbilisi_Isani',
            'WETbilisi_Krtsanisi',
            'WETbilisi_Chugureti',
            'WETbilisi_Nadzaladevi',
            'WETbilisi_Gldani',
        ];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->getChatId();
    }

    public function run()
    {
       return Telegram::message($this->chat_id, 'Не удалось обработать сообщение!')->send();
    }

    protected function getMessageBlade($bladePath, array $params) : string
    {
        return (string)view($bladePath, $params);
    }

    final public function getChatId()
    {
        if($this->request->input('callback_query'))
        {
            $this->chat_id = $this->request->input('callback_query')['from']['id'];
        }
        elseif($this->request->input('message'))
        {
            $this->chat_id = $this->request->input('message')['from']['id'];
        }
    }
}
