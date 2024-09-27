<?php

namespace App\Telegram\Webhook;

use App\Telegram\Webhook\Commands\Start;
use App\Telegram\Webhook\Commands\ChatSelect;
use App\Telegram\Webhook\Commands\StreetSelect;
use App\Telegram\Webhook\Commands\Subscribe;
use App\Telegram\Webhook\Commands\Unsubscribe;
use App\Telegram\Webhook\Documents\Document;
use \App\Telegram\Webhook\Actions\AddStreet;
use App\Telegram\Webhook\Photo\Photo;
use App\Telegram\Webhook\Error\Error;
use App\Telegram\Webhook\Text\Text;
use Illuminate\Http\Request;


class Realization
{
    public const SELECT_STREET_MESS = '↙️Ответьте↙️ на это сообщение названием улицы, например, 👉 Godziashvili';

    protected const Commands = [
        '/start' => Start::class,
        '/chat_select' => ChatSelect::class,
        '/street_select' => StreetSelect::class,
        '/subscribe' => Subscribe::class,
        '/unsubscribe' => Unsubscribe::class
    ];

    public function take(Request $request)
    {
        if(isset($request->input('message')['entities'][0]['type']))
        {
            if($request->input('message')['entities'][0]['type'] == 'bot_command')
            {
                $command_name = explode(' ', $request->input('message')['text'])[0];
                return self::Commands[$command_name] ?? Error::class;
            }
        }
        elseif($request->input('callback_query'))
        {
            $data = json_decode($request->input('callback_query')['data']);
            return '\App\Telegram\Webhook\Actions\\'.$data->action;
        }
        elseif(isset($request->input('message')['photo']))
        {
            return Photo::class;
        }
        elseif(isset($request->input('message')['document']))
        {
            return Document::class;
        }
        elseif($this->isStreetSelectAction($request))
        {
            return AddStreet::class;
        }
        elseif($request->input('message'))
        {
            return Text::class;
        }
        return false;
    }

    protected function isStreetSelectAction(Request $request) : bool
    {
        $message = $request->input('message');
        return isset($message['reply_to_message']) &&
            $message['reply_to_message']['text'] == self::SELECT_STREET_MESS;
    }
}
