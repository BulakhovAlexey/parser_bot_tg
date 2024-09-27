<?php

namespace App\ParserClient;

use App\Facades\Telegram;
use App\Models\Chat;
use App\Telegram\Webhook\Webhook;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings;
use Illuminate\Support\Carbon;

class TelegramClient
{
    private Settings $settings;
    private Settings\AppInfo $appInfo;
    private API $tgClient;

    public function __construct()
    {
        $this->settings = new Settings();
        $this->appInfo = new Settings\AppInfo();
        $this->setAppInfo();
        $this->tgClient = new API('session.madeline', $this->settings);
    }

    protected function setAppInfo() : Settings
    {
        return $this->settings
            ->setAppInfo($this->appInfo
                ->setApiId(env('TELEGRAM_CLIENT_API_ID'))
                ->setApiHash(env('TELEGRAM_CLIENT_API_HASH'))
            );

    }

    public function writeHistoryToJSON()
    {

        $this->tgClient->start();
        $result = [];

        foreach (Webhook::CHATS as $chat) {

            $updates = $this->tgClient->messages->getHistory(['peer' => '@' . $chat, 'limit' => 500]);

            foreach ($updates['messages'] as $message) {
                $result[] = $message['message'];
            }
        }

        $jsonResult = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Записываем JSON в файл
        file_put_contents(public_path('chat_histories.json'), $jsonResult);

    }

    public function sendMessagesToUsers()
    {
        $this->messagesToSent = [];
        $chats = Chat::where('confirmed', '1')->get();
        //Log::info('test', $chats->toArray());
        $this->tgClient->start();
        foreach ($chats as $chat) {
            if($chat->chat_to_parse != '' && $chat->street != '') {
                $updates = $this->tgClient->messages->getHistory(
                    [
                        'peer' => '@' . $chat->chat_to_parse,
                        'limit' => 10
                    ]
                );
                foreach ($updates['messages'] as $message) {
                    $messageDate = Carbon::createFromTimestamp($message['date']);
                    $minutesAgo = Carbon::now()->subMinute();
                    //Log::info('time', ['messdate' => $messageDate->toDateTimeString(), 'cur' => $minutesAgo->toDateTimeString()]);
                    if($messageDate->timestamp >= $minutesAgo->timestamp ){
                        if (isset($message['message']) && strpos($message['message'], $chat->street) !== false) {
                            Telegram::message($chat->recipient, $this->getMessage($chat->street, $message['message']))->send();
                            //Log::info('send', ['user' => $chat->recipient,'message' => $message['message']]);
                        }
                    }
                }
            }
        }
    }

    private function getMessage($street, $message) : string
    {
        return '‼️ 😱 ‼️' . str_replace($street, '👉<b>' . $street . '</b>👈', $message);

    }


}
