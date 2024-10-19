<?php

namespace App\Telegram\Webhook;

use App\Telegram\Webhook\Actions\AddStreet;
use App\Telegram\Webhook\Commands\Start;
use App\Telegram\Webhook\Documents\Document;
use App\Telegram\Webhook\Error\Error;
use App\Telegram\Webhook\Photo\Photo;
use App\Telegram\Webhook\Text\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class Realization
{

    protected const Commands = [
        '/start' => Start::class,
    ];

    public function take(Request $request)
    {
        if (isset($request->input('message')['entities'][0]['type'])) {
            if ($request->input('message')['entities'][0]['type'] == 'bot_command') {
                $command_name = explode(' ', $request->input('message')['text'])[0];
                return self::Commands[$command_name] ?? Error::class;
            }
        } elseif ($request->input('callback_query')) {
            $data = json_decode($request->input('callback_query')['data']);
            return '\App\Telegram\Webhook\Actions\\' . $data->action;
        } elseif (isset($request->input('message')['photo'])) {
            return Photo::class;
        } elseif (isset($request->input('message')['document'])) {
            return Document::class;
        } elseif ($request->input('message')) {
            if ($this->isStreetSelect($request)) {
                return AddStreet::class;
            }
            return Text::class;
        }
        return false;
    }

    private function isStreetSelect($request): bool
    {
        $chat_id = $request->input('message')['from']['id'];
        return Cache::get(env('STREET_SELECT_CACHE_KEY', 'street-select-') . $chat_id) === 'Y';
    }

}
