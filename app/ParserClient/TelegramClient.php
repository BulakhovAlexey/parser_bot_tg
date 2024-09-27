<?php

namespace App\ParserClient;

use App\Models\Chat;
use App\Telegram\Webhook\Webhook;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings;

class TelegramClient
{
    private Settings $settings;
    private Settings\AppInfo $appInfo;
    private Chat $chat;
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

            $updates = $this->tgClient->messages->getHistory(['peer' => '@' . $chat, 'limit' => 200]);

            foreach ($updates['messages'] as $message) {
                $result[] = $message['message'];
            }
        }

        $jsonResult = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Записываем JSON в файл
        file_put_contents(public_path('chat_histories.json'), $jsonResult);

    }


}
